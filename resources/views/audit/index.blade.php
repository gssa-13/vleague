<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" action="{{ route('audit.index') }}" class="mb-6 grid gap-4 md:grid-cols-5 md:items-end">
                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                            <select id="model" name="model" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">All</option>
                                @foreach ($models as $model)
                                    <option value="{{ $model }}" @selected(($filters['model'] ?? '') === $model)>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">All</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                            <input id="from" name="from" type="date" value="{{ $filters['from'] ?? '' }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                            <input id="to" name="to" type="date" value="{{ $filters['to'] ?? '' }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                                Filter
                            </button>

                            @can('audit.export')
                                <a href="{{ route('audit.export', array_merge($filters, ['format' => 'csv'])) }}"
                                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded">
                                    CSV
                                </a>
                                <a href="{{ route('audit.export', array_merge($filters, ['format' => 'json'])) }}"
                                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded">
                                    JSON
                                </a>
                            @endcan
                        </div>
                    </form>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 pr-4">Action</th>
                                <th class="border-b py-2 pr-4">Table</th>
                                <th class="border-b py-2 pr-4">Record</th>
                                <th class="border-b py-2 pr-4">User</th>
                                <th class="border-b py-2 pr-4">Old Value</th>
                                <th class="border-b py-2 pr-4">New Value</th>
                                <th class="border-b py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="py-2 pr-4">{{ $log->action }}</td>
                                    <td class="py-2 pr-4">{{ $log->table_name }}</td>
                                    <td class="py-2 pr-4">
                                        <a href="{{ route('audit.show', $log->id) }}" class="text-indigo-600">
                                            {{ $log->record_id }}
                                        </a>
                                    </td>
                                    <td class="py-2 pr-4">{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="py-2 pr-4">{{ $log->old_value }}</td>
                                    <td class="py-2 pr-4">{{ $log->new_value }}</td>
                                    <td class="py-2">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-gray-600">No audit logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
