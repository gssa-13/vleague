<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Competition') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->has('combination'))
                    <div class="mb-4 text-red-600">{{ $errors->first('combination') }}</div>
                @endif

                <form method="POST" action="{{ route('competitions.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Venue</label>
                        <select name="venue_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Select venue —</option>
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}"
                                    {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('venue_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tournament</label>
                        <select name="tournament_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Select tournament —</option>
                            @foreach ($tournaments as $tournament)
                                <option value="{{ $tournament->id }}"
                                    {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                    {{ $tournament->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tournament_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Division</label>
                        <select name="division_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Select division —</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
                        <a href="{{ route('competitions.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
