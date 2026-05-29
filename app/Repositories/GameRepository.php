<?php

// app/Repositories/GameRepository.php

namespace App\Repositories;

use App\Enums\GameStatus;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

class GameRepository
{
    public function all(): Collection
    {
        return Game::with('competition')->orderBy('scheduled_at')->get();
    }

    public function findById(int $id): ?Game
    {
        return Game::find($id);
    }

    public function findTrashedById(int $id): ?Game
    {
        return Game::withTrashed()->find($id);
    }

    /**
     * Checks whether another non-deleted game already occupies the same
     * field at the same scheduled time, optionally excluding a given game id.
     */
    public function hasScheduleConflict(int $fieldNumber, string $scheduledAt, ?int $excludeId = null): bool
    {
        return Game::where('field_number', $fieldNumber)
            ->where('scheduled_at', $scheduledAt)
            ->when($excludeId !== null, fn ($query) => $query->where('id', '!=', $excludeId))
            ->exists();
    }

    public function create(array $data): Game
    {
        return Game::create($data);
    }

    public function update(Game $game, array $data): Game
    {
        $game->update($data);

        return $game;
    }

    public function delete(Game $game): void
    {
        $game->delete();
    }

    public function restore(Game $game): void
    {
        $game->restore();
    }

    /**
     * Cancels a game: sets status to cancelled, records the reason and soft deletes it.
     */
    public function cancel(Game $game, int $cancellationReasonId): void
    {
        $game->update([
            'status' => GameStatus::Cancelled,
            'cancellation_reason_id' => $cancellationReasonId,
        ]);

        $game->delete();
    }
}
