<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Players') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Player::class)
                    <a href="{{ route('players.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Player</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Name</th>
                            <th class="border-b py-2 pr-4">Email</th>
                            <th class="border-b py-2 pr-4">Birth Date</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($players as $player)
                            <tr>
                                <td class="py-2 pr-4">{{ $player->first_name }} {{ $player->last_name }}</td>
                                <td class="py-2 pr-4">{{ $player->email ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $player->birth_date->format('Y-m-d') }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $player)
                                        <a href="{{ route('players.edit', $player) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('delete', $player)
                                        <form method="POST" action="{{ route('players.destroy', $player) }}" class="inline">
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
