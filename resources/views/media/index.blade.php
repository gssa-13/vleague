<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Media') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Media::class)
                    <a href="{{ route('media.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Media</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Name</th>
                            <th class="border-b py-2 pr-4">Type</th>
                            <th class="border-b py-2 pr-4">File</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($media as $item)
                            <tr>
                                <td class="py-2 pr-4">{{ $item->name }}</td>
                                <td class="py-2 pr-4">{{ $item->mediaType?->name ?? '—' }}</td>
                                <td class="py-2 pr-4">
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->file_path) }}" class="text-indigo-600" target="_blank">View</a>
                                </td>
                                <td class="py-2 space-x-2">
                                    @can('update', $item)
                                        <a href="{{ route('media.edit', $item) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('delete', $item)
                                        <form method="POST" action="{{ route('media.destroy', $item) }}" class="inline">
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
