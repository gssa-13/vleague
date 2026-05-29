<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Employees') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @can('create', App\Models\Employee::class)
                    <a href="{{ route('employees.create') }}" class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Add Employee</a>
                @endcan
                <table class="w-full text-left border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="border-b py-2 pr-4">Name</th>
                            <th class="border-b py-2 pr-4">Phone</th>
                            <th class="border-b py-2 pr-4">Hire Date</th>
                            <th class="border-b py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td class="py-2 pr-4">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td class="py-2 pr-4">{{ $employee->phone ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $employee->hire_date->format('Y-m-d') }}</td>
                                <td class="py-2 space-x-2">
                                    @can('update', $employee)
                                        <a href="{{ route('employees.edit', $employee) }}" class="text-indigo-600">Edit</a>
                                    @endcan
                                    @can('delete', $employee)
                                        <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline">
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
