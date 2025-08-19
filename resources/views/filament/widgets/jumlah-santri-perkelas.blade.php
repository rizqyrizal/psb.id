<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $this->heading }}
            </h2>
        </div>
        
        <div class="mt-4 space-y-2">
            @forelse ($this->classCounts as $classCount)
                <div class="flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-800 rounded-md">
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                        {{ $classCount->kelas->nama ?? 'Tidak Ada Kelas' }}
                    </span>
                    <span class="text-sm font-bold text-primary-500">
                        {{ $classCount->total }} Santri
                    </span>
                </div>
            @empty
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">Tidak ada data siswa.</p>
            @endforelse
        </div>
    </x-filament::card>
</x-filament::widget>