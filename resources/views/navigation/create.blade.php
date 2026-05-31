<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Navigation Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('navigation.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('navigation.fields.label') }}</label>
                        <input type="text" name="label" value="{{ old('label') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('label') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('navigation.fields.label_key') }}</label>
                        <input type="text" name="label_key" value="{{ old('label_key') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               placeholder="navigation.dashboard">
                        @error('label_key') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('navigation.fields.route_name') }}</label>
                        <input type="text" name="route_name" value="{{ old('route_name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('route_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('navigation.fields.icon') }}</label>
                        <input type="text" name="icon" value="{{ old('icon') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               placeholder="fas fa-circle">
                        @error('icon') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('navigation.fields.permission_name') }}</label>
                        <input type="text" name="permission_name" value="{{ old('permission_name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               placeholder="venues.view">
                        @error('permission_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('navigation.fields.sort_order') }}</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('sort_order') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded">
                            {{ __('common.create') }}
                        </button>
                        <a href="{{ route('navigation.index') }}" class="text-gray-600">{{ __('common.cancel') }}</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
