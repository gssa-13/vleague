<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Competitions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    @can('create', App\Models\Competition::class)
                        <a href="{{ route('competitions.create') }}"
                           class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">
                            Add Competition
                        </a>
                    @endcan

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 pr-4">Venue</th>
                                <th class="border-b py-2 pr-4">Tournament</th>
                                <th class="border-b py-2 pr-4">Division</th>
                                <th class="border-b py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($competitions as $competition)
                                <tr>
                                    <td class="py-2 pr-4">{{ $competition->venue->name }}</td>
                                    <td class="py-2 pr-4">{{ $competition->tournament->name }}</td>
                                    <td class="py-2 pr-4">{{ $competition->division->name }}</td>
                                    <td class="py-2">
                                        @can('delete', $competition)
                                            <form method="POST"
                                                  action="{{ route('competitions.destroy', $competition) }}"
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
