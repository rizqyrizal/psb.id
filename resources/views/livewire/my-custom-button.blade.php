<div x-data="{ open: false }">
    {{-- Tombol di topbar --}}
    <x-filament::button
        icon="heroicon-o-adjustments-vertical"
        size="md"
        @click="open = true"
    >
        Switch Mode
    </x-filament::button>

    {{-- Modal Pop-up yang Diperbaiki --}}
    <div
        x-show="open"
        x-cloak
        class="fi-modal fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 transition duration-300 ease-out sm:px-6 sm:py-0"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 sm:translate-y-0 sm:scale-95"
    >
        <div
            class="fi-modal-content w-full max-w-lg overflow-hidden rounded-xl bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-gray-300/20"
            @click.away="open = false"
        >
            {{-- Tombol Tutup (X) --}}
            <div class="flex justify-end px-4 pt-4">
                <button
                    type="button"
                    class="rounded-full p-1 text-gray-400 hover:text-gray-900 dark:text-gray-500 dark:hover:text-gray-300"
                    @click="open = false"
                >
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            {{-- Isi Modal (Ikon & Teks) --}}
            <div class="fi-modal-header text-center px-6 pt-0">
                <div class="mb-4 flex justify-center">
                    <div class="rounded-full bg-gray-100 p-3 dark:bg-gray-800">
                        <x-heroicon-o-adjustments-vertical class="h-6 w-6 text-gray-500" />
                    </div>
                </div>

                <h3 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                    Switch Mode
                </h3>
            </div>

            <div class="fi-modal-content overflow-y-auto px-6 text-center">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Pilih mode yang ingin Anda gunakan.
                </p>
            </div>

            {{-- Footer dengan Tombol Aksi --}}
            <div class="fi-modal-footer flex items-center justify-center gap-x-3 p-6">
                <x-filament::button
                    color="warning" {{-- Menggunakan warna "warning" agar warnanya oranye --}}
                    @click="open = false"
                    wire:click="handleModeChange('psb')"
                >
                    PSB
                </x-filament::button>

                <x-filament::button
                    color="warning"
                    @click="open = false"
                    wire:click="handleModeChange('sekol')"
                >
                    SEKOL
                </x-filament::button>

                <x-filament::button
                    color="warning"
                    @click="open = false"
                    wire:click="handleModeChange('tu')"
                >
                    TU
                </x-filament::button>
            </div>
        </div>
    </div>
</div>