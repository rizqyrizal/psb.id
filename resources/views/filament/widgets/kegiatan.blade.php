<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $this->heading }}
            </h2>
            <a href="{{ \App\Filament\Resources\AcaraResource::getUrl('index') }}" class="text-sm text-primary-500 hover:text-primary-600 transition-all duration-200">
                Lihat Semua
            </a>
        </div>

        <div class="mt-4 space-y-4">
            @forelse ($this->latestAcaras as $acara)
            <div class="overflow-hidden rounded-lg shadow-sm bg-gray-50 dark:bg-gray-800">
                <img src="{{ asset('storage/' . $acara->foto) }}"
                    alt="Foto Acara {{ $acara->nama }}"
                    class="w-full h-40 object-cover cursor-pointer"
                    wire:click="showDescription({{ $acara->id }})">

                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ $acara->nama }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($acara->waktu)->isoFormat('dddd, D MMMM Y') }} di {{ $acara->tempat }}
                    </p>
                </div>

                @if ($selectedAcaraId === $acara->id)
                <div class="p-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border-t border-gray-200 dark:border-gray-600">
                    {{ $acara->desc }}
                </div>
                @endif
            </div>
            @empty
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">Tidak ada acara terbaru.</div>
            @endforelse
        </div>
    </x-filament::card>
</x-filament::widget>