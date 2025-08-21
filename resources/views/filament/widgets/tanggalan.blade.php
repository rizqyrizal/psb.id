<x-filament-widgets::widget>
    <div class="rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-4 mx-auto max-w-xl">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 text-sm font-medium text-gray-950 dark:text-white" for="filter-date">
                    Tanggal
                </label>
                <div class="relative mt-1">
                    <div class="fi-input-wrapper flex rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                        <div class="fi-select-input flex-1 block w-full py-6 pe-3 ps-3 text-2xl font-bold text-center sm:text-2xl sm:leading-6" style="color: #F97316;">
                            {{ now()->translatedFormat('d') }}
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="mb-1 text-sm font-medium text-gray-950 dark:text-white" for="filter-month">
                    Bulan
                </label>
                <div class="relative mt-1">
                    <div class="fi-input-wrapper flex rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                        <div class="fi-select-input flex-1 block w-full py-6 pe-3 text-2xl font-bold text-center sm:text-2xl sm:leading-6" style="color: #F97316;">
                            {{ now()->translatedFormat('F Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-filament-widgets::widget>