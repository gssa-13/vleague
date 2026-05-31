<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Financial Report') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="GET" action="{{ route('reports.financial.index') }}" class="mb-6 flex items-end gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Venue</label>
                        <select name="venue_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}" {{ $venueId == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Generate</button>
                    @can('reports.export')
                        <a href="{{ route('reports.financial.export', ['venue_id' => $venueId]) }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Export CSV</a>
                    @endcan
                </form>

                @if ($report)
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <tr>
                                <td class="border-b py-2 pr-4 font-medium">Total Income</td>
                                <td class="border-b py-2 text-right">{{ number_format($report['total_income'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="border-b py-2 pr-4 font-medium">Total Expenses</td>
                                <td class="border-b py-2 text-right">{{ number_format($report['total_expenses'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold">Net</td>
                                <td class="py-2 text-right font-semibold">{{ number_format($report['net'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-600">No venue selected.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
