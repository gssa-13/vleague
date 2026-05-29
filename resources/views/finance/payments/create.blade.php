<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Payment') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('payments.store') }}">
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
                        <label class="block text-sm font-medium text-gray-700">Concept</label>
                        <select name="concept" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (App\Enums\PaymentConcept::cases() as $concept)
                                <option value="{{ $concept->value }}" {{ old('concept') === $concept->value ? 'selected' : '' }}>{{ $concept->label() }}</option>
                            @endforeach
                        </select>
                        @error('concept') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (App\Enums\PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}" {{ old('payment_method') === $method->value ? 'selected' : '' }}>{{ $method->label() }}</option>
                            @endforeach
                        </select>
                        @error('payment_method') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Paid Amount</label>
                            <input type="number" step="0.01" name="paid_amount" value="{{ old('paid_amount', 0) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('paid_amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
                        <a href="{{ route('payments.index') }}" class="text-gray-600">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
