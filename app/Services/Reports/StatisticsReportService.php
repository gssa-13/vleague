<?php

// app/Services/Reports/StatisticsReportService.php

namespace App\Services\Reports;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Repositories\Reports\ReportRepository;

class StatisticsReportService
{
    public function __construct(
        private readonly ReportRepository $repository,
    ) {}

    /**
     * Generates a statistics report for a competition: games played,
     * total goals and average goals per played game.
     *
     * @return array{games_played: int, total_goals: int, average_goals: float}
     */
    public function generate(int $competitionId, bool $withTrashed = false): array
    {
        $games = $this->repository->gamesForCompetition($competitionId, $withTrashed)
            ->filter(fn (Game $game) => $game->status === GameStatus::Played);

        $played = $games->count();
        $goals = (int) $games->sum(
            fn (Game $game) => (int) $game->home_score + (int) $game->away_score
        );

        return [
            'games_played' => $played,
            'total_goals' => $goals,
            'average_goals' => $played > 0 ? round($goals / $played, 2) : 0.0,
        ];
    }
}
