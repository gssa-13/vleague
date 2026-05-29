<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Navigation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    @can('create', App\Models\NavigationItem::class)
                        <a href="{{ route('navigation.create') }}"
                           class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">
                            Add Item
                        </a>
                    @endcan

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 pr-4">Label</th>
                                <th class="border-b py-2 pr-4">Route</th>
                                <th class="border-b py-2 pr-4">Permission</th>
                                <th class="border-b py-2 pr-4">Order</th>
                                <th class="border-b py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="py-2 pr-4">{{ $item->label }}</td>
                                    <td class="py-2 pr-4">{{ $item->route_name ?? '—' }}</td>
                                    <td class="py-2 pr-4">{{ $item->permission_name ?? '—' }}</td>
                                    <td class="py-2 pr-4">{{ $item->sort_order }}</td>
                                    <td class="py-2 space-x-2">
                                        @can('update', $item)
                                            <a href="{{ route('navigation.edit', $item) }}"
                                               class="text-indigo-600">Edit</a>
                                        @endcan
                                        @can('delete', $item)
                                            <form method="POST"
                                                  action="{{ route('navigation.destroy', $item) }}"
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
