<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Payroll') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Payroll::class)
                    <a href="{{ route('payrolls.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Payroll</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Venue</th>
                            <th class="border-b py-2 pr-4">Period</th>
                            <th class="border-b py-2 pr-4">Status</th>
                            <th class="border-b py-2 pr-4">Total</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrolls as $payroll)
                            <tr>
                                <td class="py-2 pr-4">{{ $payroll->venue->name }}</td>
                                <td class="py-2 pr-4">{{ $payroll->period }}</td>
                                <td class="py-2 pr-4">{{ $payroll->status->label() }}</td>
                                <td class="py-2 pr-4">{{ number_format($payroll->total, 2) }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $payroll)
                                        <a href="{{ route('payrolls.edit', $payroll) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('cancel', $payroll)
                                        <form method="POST" action="{{ route('payrolls.cancel', $payroll) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-orange-600">Cancel</button>
                                        </form>
                                    @endcan
                                    @can('delete', $payroll)
                                        <form method="POST" action="{{ route('payrolls.destroy', $payroll) }}" class="inline">
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
