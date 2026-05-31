<?php

// tests/Feature/Reports/FinancialReportTest.php

use App\Enums\PaymentStatus;
use App\Models\Competition;
use App\Models\Division;
use App\Models\Payment;
use App\Models\PaymentExpense;
use App\Models\Tournament;
use App\Models\Venue;
use App\Services\Reports\FinancialReportService;

beforeEach(function () {
    $this->service = app(FinancialReportService::class);
});

it('computes correct income, expense and net totals against a controlled dataset', function () {
    $venue = Venue::factory()->create();
    $tournament = Tournament::factory()->create(['venue_id' => $venue->id]);
    $division = Division::factory()->create(['tournament_id' => $tournament->id]);
    $competition = Competition::factory()->create([
        'venue_id' => $venue->id,
        'tournament_id' => $tournament->id,
        'division_id' => $division->id,
    ]);

    Payment::factory()->create([
        'competition_id' => $competition->id,
        'paid_amount' => 800,
        'status' => PaymentStatus::Paid,
    ]);
    Payment::factory()->create([
        'competition_id' => $competition->id,
        'paid_amount' => 200,
        'status' => PaymentStatus::Partial,
    ]);

    PaymentExpense::factory()->create(['venue_id' => $venue->id, 'amount' => 150]);

    $report = $this->service->generate($venue->id);

    expect($report['total_income'])->toBe(1000.0)
        ->and($report['total_expenses'])->toBe(150.0)
        ->and($report['net'])->toBe(850.0);
});

it('excludes soft-deleted expenses from the financial report by default', function () {
    $venue = Venue::factory()->create();

    PaymentExpense::factory()->create(['venue_id' => $venue->id, 'amount' => 100]);
    $deleted = PaymentExpense::factory()->create(['venue_id' => $venue->id, 'amount' => 999]);
    $deleted->delete();

    $report = $this->service->generate($venue->id);

    expect($report['total_expenses'])->toBe(100.0);
});

it('includes soft-deleted expenses when withTrashed is requested', function () {
    $venue = Venue::factory()->create();

    PaymentExpense::factory()->create(['venue_id' => $venue->id, 'amount' => 100]);
    $deleted = PaymentExpense::factory()->create(['venue_id' => $venue->id, 'amount' => 50]);
    $deleted->delete();

    $report = $this->service->generate($venue->id, withTrashed: true);

    expect($report['total_expenses'])->toBe(150.0);
});
