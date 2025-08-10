<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Mail\GuruInvitationEmail;
use App\Mail\GuruCredentialsEmail;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InviteGuruWidget extends Widget implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $email;
    public $invitations = [];

    protected static string $view = 'filament.resources.user-resource.widgets.invite-guru-widget';

    public function mount()
    {
        $this->loadInvitations();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),
        ];
    }

    public function sendInvitation(): void
    {
        if (!$this->email) {
            Notification::make()->title('Email tidak boleh kosong.')->danger()->send();
            return;
        }

        if (User::where('email', $this->email)->exists()) {
            Notification::make()->title('Email sudah terdaftar.')->danger()->send();
            return;
        }

        // Generate password angka acak
        $randomPassword = $this->generateRandomNumber(10);

        $user = User::create([
            'name' => '-',
            'email' => $this->email,
            'status' => 'pending',
            'password' => Hash::make($randomPassword),
        ]);

        // Kirim email undangan
        Mail::to($user->email)->send(new GuruInvitationEmail($user));

        // Kirim email password
        Mail::to($user->email)->send(new GuruCredentialsEmail($user, $randomPassword));

        // Ubah status jadi sukses setelah beberapa detik
        $user->update(['status' => 'success']);

        $this->loadInvitations();

        Notification::make()
            ->title('Undangan & kredensial berhasil dikirim ke ' . $user->email)
            ->success()
            ->send();

        $this->reset('email');
    }

    private function generateRandomNumber($length)
    {
        $numbers = '0123456789';
        return substr(str_shuffle(str_repeat($numbers, $length)), 0, $length);
    }

    public function loadInvitations()
    {
        $this->invitations = User::whereIn('status', ['pending', 'success'])
            ->latest()
            ->get(['id', 'name', 'email', 'status'])
            ->toArray();
    }

    public function deleteInvitation($id)
    {
        User::find($id)?->delete();
        $this->loadInvitations();
    }
}
