<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class MyCustomButton extends Component
{
    public function render()
    {
        return view('livewire.my-custom-button');
    }

    public function handleModeChange(string $mode)
    {
        session(['selected_mode' => $mode]);

        Notification::make()
            ->title("Mode '{$mode}' berhasil diaktifkan!")
            ->success()
            ->send();

        return redirect(request()->header('Referer'));
    }
}