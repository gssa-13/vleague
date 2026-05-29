<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Prices') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Price::class)
                    <a href="{{ route('prices.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Price</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Name</th>
                            <th class="border-b py-2 pr-4">Amount</th>
                            <th class="border-b py-2 pr-4">Currency</th>
                            <th class="border-b py-2 pr-4">Status</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prices as $price)
                            <tr>
                                <td class="py-2 pr-4">{{ $price->name }}</td>
                                <td class="py-2 pr-4">{{ number_format($price->amount, 2) }}</td>
                                <td class="py-2 pr-4">{{ $price->currency }}</td>
                                <td class="py-2 pr-4">{{ $price->status->value }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $price)
                                        <a href="{{ route('prices.edit', $price) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('delete', $price)
                                        <form method="POST" action="{{ route('prices.destroy', $price) }}" class="inline">
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
