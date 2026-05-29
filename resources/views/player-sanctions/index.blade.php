<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Player Sanctions') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\PlayerSanction::class)
                    <a href="{{ route('player-sanctions.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Sanction</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Player</th>
                            <th class="border-b py-2 pr-4">Reason</th>
                            <th class="border-b py-2 pr-4">Games (served/total)</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sanctions as $sanction)
                            <tr>
                                <td class="py-2 pr-4">{{ $sanction->player->first_name }} {{ $sanction->player->last_name }}</td>
                                <td class="py-2 pr-4">{{ $sanction->reason }}</td>
                                <td class="py-2 pr-4">{{ $sanction->served_games }}/{{ $sanction->sanctioned_games }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $sanction)
                                        <a href="{{ route('player-sanctions.edit', $sanction) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('delete', $sanction)
                                        <form method="POST" action="{{ route('player-sanctions.destroy', $sanction) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
