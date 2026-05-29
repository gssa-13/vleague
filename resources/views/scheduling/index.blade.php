<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Games') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Game::class)
                    <a href="{{ route('games.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Game</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Competition</th>
                            <th class="border-b py-2 pr-4">Type</th>
                            <th class="border-b py-2 pr-4">Scheduled</th>
                            <th class="border-b py-2 pr-4">Field</th>
                            <th class="border-b py-2 pr-4">Status</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($games as $game)
                            <tr>
                                <td class="py-2 pr-4">#{{ $game->competition_id }}</td>
                                <td class="py-2 pr-4">{{ $game->game_type->label() }}</td>
                                <td class="py-2 pr-4">{{ $game->scheduled_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2 pr-4">{{ $game->field_number ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $game->status->label() }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $game)
                                        <a href="{{ route('games.edit', $game) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('cancel', $game)
                                        <form method="POST" action="{{ route('games.cancel', $game) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-orange-600">Cancel</button>
                                        </form>
                                    @endcan
                                    @can('delete', $game)
                                        <form method="POST" action="{{ route('games.destroy', $game) }}" class="inline">
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
