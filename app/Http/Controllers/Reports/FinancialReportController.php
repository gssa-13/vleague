<?php

// app/Http/Controllers/Reports/FinancialReportController.php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Services\Reports\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinancialReportController extends Controller
{
    public function __construct(
        private readonly FinancialReportService $service,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('reports.view');

        $venues = Venue::all();
        $venueId = $request->integer('venue_id') ?: $venues->first()?->id;
        $report = $venueId ? $this->service->generate($venueId) : null;

        return view('reports.financial', compact('venues', 'venueId', 'report'));
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('reports.export');

        $venueId = $request->integer('venue_id') ?: Venue::query()->value('id');
        $report = $venueId ? $this->service->generate($venueId) : [
            'total_income' => 0.0,
            'total_expenses' => 0.0,
            'net' => 0.0,
        ];

        $callback = function () use ($report) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Metric', 'Amount']);
            fputcsv($handle, ['Total Income', $report['total_income']]);
            fputcsv($handle, ['Total Expenses', $report['total_expenses']]);
            fputcsv($handle, ['Net', $report['net']]);
            fclose($handle);
        };

        return response()->streamDownload($callback, 'financial-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
