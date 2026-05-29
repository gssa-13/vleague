<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Division') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('divisions.store') }}">
                    @csrf

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
                        <label class="block text-sm font-medium text-gray-700">Day</label>
                        <select name="day" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Select day —</option>
                            @foreach (App\Enums\DivisionDay::cases() as $day)
                                <option value="{{ $day->value }}"
                                    {{ old('day') === $day->value ? 'selected' : '' }}>
                                    {{ $day->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('day') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Field Number</label>
                        <input type="number" name="field_number" value="{{ old('field_number', 1) }}" min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('field_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Group Letter (optional)</label>
                        <input type="text" name="group_letter" value="{{ old('group_letter') }}"
                               maxlength="1" placeholder="A"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('group_letter') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
                        <a href="{{ route('divisions.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
