<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Log Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <a href="{{ route('audit.index') }}" class="text-indigo-600">Back to audit logs</a>
                    </div>

                    <dl class="grid gap-4 md:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Action</dt>
                            <dd class="mt-1">{{ $log->action }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Table</dt>
                            <dd class="mt-1">{{ $log->table_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Model</dt>
                            <dd class="mt-1">{{ $log->model }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Record ID</dt>
                            <dd class="mt-1">{{ $log->record_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Column</dt>
                            <dd class="mt-1">{{ $log->column_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">User</dt>
                            <dd class="mt-1">{{ $log->user?->name ?? 'System' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">IP</dt>
                            <dd class="mt-1">{{ $log->user_ip ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Date</dt>
                            <dd class="mt-1">{{ $log->created_at?->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-8 grid gap-4 md:grid-cols-2">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Old Value</h3>
                            <pre class="mt-2 whitespace-pre-wrap rounded bg-gray-100 p-4 text-sm">{{ $log->old_value ?? 'N/A' }}</pre>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">New Value</h3>
                            <pre class="mt-2 whitespace-pre-wrap rounded bg-gray-100 p-4 text-sm">{{ $log->new_value ?? 'N/A' }}</pre>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
