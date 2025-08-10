<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                Buat Tim Baru
            </h2>
        </div>

        <form wire:submit.prevent="createTeam">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Buat Tim
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament::widget>