<?php

// app/Services/Reports/OperationalReportService.php

namespace App\Services\Reports;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Repositories\Reports\ReportRepository;

class OperationalReportService
{
    public function __construct(
        private readonly ReportRepository $repository,
    ) {}

    /**
     * Generates an operational report for a competition: counts of games
     * by status (scheduled, played, cancelled, postponed).
     *
     * @return array<string, int>
     */
    public function generate(int $competitionId, bool $withTrashed = false): array
    {
        $games = $this->repository->gamesForCompetition($competitionId, $withTrashed);

        $counts = [];

        foreach (GameStatus::cases() as $status) {
            $counts[$status->value] = $games
                ->filter(fn (Game $game) => $game->status === $status)
                ->count();
        }

        return $counts;
    }
}
