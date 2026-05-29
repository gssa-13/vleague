<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Game') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('games.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Competition</label>
                        <select name="competition_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Select competition —</option>
                            @foreach ($competitions as $competition)
                                <option value="{{ $competition->id }}" {{ old('competition_id') == $competition->id ? 'selected' : '' }}>
                                    #{{ $competition->id }} — {{ $competition->division?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('competition_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="game_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (App\Enums\GameType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('game_type') === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('game_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Scheduled At</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('scheduled_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Field Number</label>
                        <input type="number" name="field_number" value="{{ old('field_number') }}" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('field_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Matchday</label>
                        <input type="number" name="matchday" value="{{ old('matchday') }}" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('matchday') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
                        <a href="{{ route('games.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
