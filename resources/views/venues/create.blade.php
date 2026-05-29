<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Venue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('venues.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Max Fields</label>
                        <input type="number" name="max_fields" value="{{ old('max_fields', 1) }}" min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('max_fields') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Match Duration (minutes)</label>
                        <input type="number" name="match_duration_minutes"
                               value="{{ old('match_duration_minutes', 50) }}" min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('match_duration_minutes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Advance Booking Days</label>
                        <input type="number" name="advance_booking_days"
                               value="{{ old('advance_booking_days', 0) }}" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('advance_booking_days')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded">
                            Create
                        </button>
                        <a href="{{ route('venues.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
