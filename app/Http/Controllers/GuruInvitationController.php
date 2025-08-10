<?php

namespace App\Http\Controllers;

use App\Mail\GuruCredentialsEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GuruInvitationController extends Controller
{
    /**
     * Auto-generate password dan kirim via email,
     * tampilkan halaman konfirmasi dengan password.
     */
    public function agreeInvitation(Request $request)
    {
        $user = User::where('remember_token', $request->token)->firstOrFail();

        // Generate password random 10 digit angka
        $randomPassword = $this->generateRandomNumber(10);

        // Update user dengan password baru, hapus token, ubah status
        $user->forceFill([
            'password'       => Hash::make($randomPassword),
            'remember_token' => null,
            'status'         => 'running',
        ])->save();

        // Kirim email kredensial ke user
        Mail::to($user->email)->send(new GuruCredentialsEmail($user, $randomPassword));

        // Tampilkan halaman konfirmasi agar user lihat password langsung
        return view('guru.agree-confirmation', [
            'name'     => $user->name,
            'email'    => $user->email,
            'password' => $randomPassword,
        ]);
    }

    /**
     * Fungsi generate angka acak sepanjang $length.
     */
    private function generateRandomNumber($length)
    {
        $numbers = '0123456789';
        return substr(str_shuffle(str_repeat($numbers, $length)), 0, $length);
    }
}
