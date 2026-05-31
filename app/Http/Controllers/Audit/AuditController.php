<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $service,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('audit.view');

        $filters = $this->filtersFrom($request);
        $logs = $this->service->search($filters);
        $models = $this->service->availableModels();
        $users = $this->service->availableUsers();

        return view('audit.index', compact('filters', 'logs', 'models', 'users'));
    }

    public function show(int $id): View
    {
        $this->authorize('audit.view');

        $log = $this->service->findById($id);
        abort_if($log === null, 404);

        return view('audit.show', compact('log'));
    }

    public function export(Request $request): Response|JsonResponse
    {
        $this->authorize('audit.export');

        $rows = $this->service->exportRows($this->filtersFrom($request));

        if (strtolower((string) $request->query('format')) === 'json') {
            return response()->json(['data' => $rows]);
        }

        return response($this->csvFromRows($rows), 200, [
            'Content-Disposition' => 'attachment; filename="audit-logs.csv"',
            'Content-Type' => 'text/csv',
        ]);
    }

    private function filtersFrom(Request $request): array
    {
        return array_filter(
            $request->only(['model', 'user_id', 'from', 'to']),
            fn ($value) => $value !== null && $value !== ''
        );
    }

    private function csvFromRows(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $this->csvHeaders());

        foreach ($rows as $row) {
            fputcsv($handle, $this->csvRow($row));
        }

        rewind($handle);

        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    private function csvHeaders(): array
    {
        return ['ID', 'Action', 'Model', 'Table', 'Record ID', 'Column', 'Old Value', 'New Value', 'User', 'IP', 'Created At'];
    }

    private function csvRow(array $row): array
    {
        return [
            $row['id'],
            $row['action'],
            $row['model'],
            $row['table_name'],
            $row['record_id'],
            $row['column_name'],
            $row['old_value'],
            $row['new_value'],
            $row['user']['name'] ?? '',
            $row['user_ip'],
            $row['created_at'],
        ];
    }
}
