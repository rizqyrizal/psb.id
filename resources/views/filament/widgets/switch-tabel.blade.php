<div class="flex justify-end items-center gap-2 w-full">
    <x-filament::button
        size="sm"
        color="{{ $activeTable === 'uang_masuk' ? 'success' : 'secondary' }}"
        wire:click="$set('activeTable', 'uang_masuk')"
    >
        Uang Masuk
    </x-filament::button>

    <x-filament::button
        size="sm"
        color="{{ $activeTable === 'absensi' ? 'success' : 'secondary' }}"
        wire:click="$set('activeTable', 'absensi')"
    >
        Absensi
    </x-filament::button>
</div>
