<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Match Roles') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\GameRole::class)
                    <a href="{{ route('game-roles.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Role</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Name</th>
                            <th class="border-b py-2 pr-4">Description</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gameRoles as $gameRole)
                            <tr>
                                <td class="py-2 pr-4">{{ $gameRole->name }}</td>
                                <td class="py-2 pr-4">{{ $gameRole->description ?? '—' }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $gameRole)
                                        <a href="{{ route('game-roles.edit', $gameRole) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('delete', $gameRole)
                                        <form method="POST" action="{{ route('game-roles.destroy', $gameRole) }}" class="inline">
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
