<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tournaments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    @can('create', App\Models\Tournament::class)
                        <a href="{{ route('tournaments.create') }}"
                           class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">
                            Add Tournament
                        </a>
                    @endcan

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 pr-4">Name</th>
                                <th class="border-b py-2 pr-4">Venue</th>
                                <th class="border-b py-2 pr-4">Status</th>
                                <th class="border-b py-2 pr-4">Starts</th>
                                <th class="border-b py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tournaments as $tournament)
                                <tr>
                                    <td class="py-2 pr-4">{{ $tournament->name }}</td>
                                    <td class="py-2 pr-4">{{ $tournament->venue->name }}</td>
                                    <td class="py-2 pr-4">{{ $tournament->status->value }}</td>
                                    <td class="py-2 pr-4">{{ $tournament->starts_at->format('Y-m-d') }}</td>
                                    <td class="py-2 space-x-2">
                                        @can('update', $tournament)
                                            <a href="{{ route('tournaments.edit', $tournament) }}"
                                               class="text-indigo-600">Edit</a>
                                        @endcan
                                        @can('delete', $tournament)
                                            <form method="POST"
                                                  action="{{ route('tournaments.destroy', $tournament) }}"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
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
    </div>
</x-app-layout>
