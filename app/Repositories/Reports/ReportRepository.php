<?php

// app/Repositories/Reports/ReportRepository.php

namespace App\Repositories\Reports;

use App\Models\Game;
use App\Models\Payment;
use App\Models\PaymentExpense;
use Illuminate\Database\Eloquent\Collection;

class ReportRepository
{
    /**
     * Payments linked to a venue through their competition.
     * Soft-deleted payments are excluded unless $withTrashed is true.
     */
    public function paymentsForVenue(int $venueId, bool $withTrashed = false): Collection
    {
        return Payment::query()
            ->when($withTrashed, fn ($query) => $query->withTrashed())
            ->whereHas('competition', fn ($query) => $query->where('venue_id', $venueId))
            ->get();
    }

    /**
     * Expenses registered for a venue.
     * Soft-deleted expenses are excluded unless $withTrashed is true.
     */
    public function expensesForVenue(int $venueId, bool $withTrashed = false): Collection
    {
        return PaymentExpense::query()
            ->when($withTrashed, fn ($query) => $query->withTrashed())
            ->where('venue_id', $venueId)
            ->get();
    }

    /**
     * Games played within a competition for statistics reports.
     */
    public function gamesForCompetition(int $competitionId, bool $withTrashed = false): Collection
    {
        return Game::query()
            ->when($withTrashed, fn ($query) => $query->withTrashed())
            ->where('competition_id', $competitionId)
            ->get();
    }
}
