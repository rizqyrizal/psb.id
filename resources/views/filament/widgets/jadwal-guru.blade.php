<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $this->heading }} ({{ now()->isoFormat('dddd, D MMMM Y') }})
            </h2>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($this->schedules as $kelasNama => $jadwals)
                <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <h3 class="text-md font-semibold text-primary-500">
                        {{ $kelasNama }}
                    </h3>
                    <ul class="mt-2 space-y-2">
                        @foreach ($jadwals as $jadwal)
                            <li class="flex justify-between items-center text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ $jadwal->mapel->nama ?? 'Tidak Ditemukan' }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">Tidak ada jadwal hari ini.</div>
            @endforelse
        </div>
        
    </x-filament::card>
</x-filament::widget>