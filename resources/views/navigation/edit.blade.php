<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Navigation Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('navigation.update', $item) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Label</label>
                        <input type="text" name="label" value="{{ old('label', $item->label) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('label') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Route Name</label>
                        <input type="text" name="route_name" value="{{ old('route_name', $item->route_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('route_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Icon</label>
                        <input type="text" name="icon" value="{{ old('icon', $item->icon) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('icon') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Permission Name (optional)</label>
                        <input type="text" name="permission_name"
                               value="{{ old('permission_name', $item->permission_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('permission_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('sort_order') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded">
                            Update
                        </button>
                        <a href="{{ route('navigation.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
