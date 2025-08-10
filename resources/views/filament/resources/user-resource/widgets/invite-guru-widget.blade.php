<x-filament::widget>
    <x-filament::card>
        <form wire:submit.prevent="sendInvitation" class="space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary">
                Kirim Undangan
            </x-filament::button>
        </form>

        @if(count($this->invitations) > 0)
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach ($this->invitations as $invitation)
                    <tr>
                        <td class="px-4 py-3 text-sm text-white">{{ $invitation['name'] ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-white">{{ $invitation['email'] }}</td>
                        <td class="px-4 py-3">
                            @if($invitation['status'] === 'pending')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <!-- Icon tanda seru -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Pending
                            </span>
                            @elseif($invitation['status'] === 'success')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <!-- Icon centang -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Sukses
                            </span>
                            @endif

                        </td>
                        <td class="px-4 py-3 text-center">
                            <x-filament::button color="danger" size="sm" wire:click="deleteInvitation({{ $invitation['id'] }})">
                                Hapus
                            </x-filament::button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </x-filament::card>
</x-filament::widget>