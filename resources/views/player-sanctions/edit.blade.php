<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Player Sanction') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('player-sanctions.update', $sanction) }}">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Player</label>
                        <select name="player_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($players as $player)
                                <option value="{{ $player->id }}" {{ old('player_id', $sanction->player_id) == $player->id ? 'selected' : '' }}>
                                    {{ $player->first_name }} {{ $player->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('player_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Reason</label>
                        <input type="text" name="reason" value="{{ old('reason', $sanction->reason) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('reason') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sanctioned Games</label>
                        <input type="number" name="sanctioned_games" value="{{ old('sanctioned_games', $sanction->sanctioned_games) }}" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('sanctioned_games') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Served Games</label>
                        <input type="number" name="served_games" value="{{ old('served_games', $sanction->served_games) }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('served_games') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                        <a href="{{ route('player-sanctions.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
