<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Payments') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Payment::class)
                    <a href="{{ route('payments.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Payment</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Concept</th>
                            <th class="border-b py-2 pr-4">Method</th>
                            <th class="border-b py-2 pr-4">Amount</th>
                            <th class="border-b py-2 pr-4">Paid</th>
                            <th class="border-b py-2 pr-4">Debt</th>
                            <th class="border-b py-2 pr-4">Status</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="py-2 pr-4">{{ $payment->concept->label() }}</td>
                                <td class="py-2 pr-4">{{ $payment->payment_method->label() }}</td>
                                <td class="py-2 pr-4">{{ number_format($payment->amount, 2) }}</td>
                                <td class="py-2 pr-4">{{ number_format($payment->paid_amount, 2) }}</td>
                                <td class="py-2 pr-4">{{ number_format($payment->debt, 2) }}</td>
                                <td class="py-2 pr-4">{{ $payment->status->label() }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $payment)
                                        <a href="{{ route('payments.edit', $payment) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('cancel', $payment)
                                        <form method="POST" action="{{ route('payments.cancel', $payment) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="reason" value="Cancelled from list">
                                            <button type="submit" class="text-orange-600">Cancel</button>
                                        </form>
                                    @endcan
                                    @can('delete', $payment)
                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="inline">
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
