<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $this->heading }}
            </h2>
            <div class="flex items-center mb-2 gap-2">
                <x-filament::button
                    wire:click="switchTab('acara')"
                    size="lg"
                    color="{{ $activeTab === 'acara' ? 'success' : 'secondary' }}">
                    Acara
                </x-filament::button>

                <x-filament::button
                    wire:click="switchTab('donasi')"
                    size="lg"
                    color="{{ $activeTab === 'donasi' ? 'success' : 'secondary' }}">
                    Donasi
                </x-filament::button>
            </div>

        </div>

        <div class="mt-4 space-y-4">
            @forelse ($this->latestItems as $item)
            <div class="overflow-hidden rounded-lg shadow-sm bg-gray-50 dark:bg-gray-800">

                {{-- Jika Acara --}}
                @if ($activeTab === 'acara')
                <img src="{{ asset('storage/' . $item->foto) }}"
                    alt="Foto Acara {{ $item->nama }}"
                    class="w-full h-40 object-cover cursor-pointer"
                    wire:click="showDescription({{ $item->id }})">

                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ $item->nama }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($item->waktu)->isoFormat('dddd, D MMMM Y') }} di {{ $item->tempat }}
                    </p>
                </div>

                @else
                <div class="p-4 cursor-pointer" wire:click="showDescription({{ $item->id }})">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ $item->nama }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Untuk: {{ $item->penerima }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($item->waktu)->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>
                @endif

                @if ($selectedId === $item->id)
                <div class="p-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border-t border-gray-200 dark:border-gray-600">
                    {{ $item->desc }}
                </div>
                @endif
            </div>
            @empty
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                Tidak ada {{ $activeTab }} terbaru.
            </div>
            @endforelse
        </div>
        
    </x-filament::card>
</x-filament::widget>