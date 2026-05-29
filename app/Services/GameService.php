<?php

// app/Services/GameService.php

namespace App\Services;

use App\Models\Game;
use App\Repositories\GameRepository;
use Illuminate\Database\Eloquent\Collection;

class GameService
{
    public function __construct(
        private readonly GameRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Game
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Game
    {
        return $this->repository->findTrashedById($id);
    }

    /**
     * Validates that a schedule date is not in the past.
     */
    public function isValidScheduleDate(string $scheduledAt): bool
    {
        return strtotime($scheduledAt) >= time();
    }

    public function create(array $data): Game
    {
        $fieldNumber = $data['field_number'] ?? null;

        if ($fieldNumber !== null
            && $this->repository->hasScheduleConflict($fieldNumber, $data['scheduled_at'], null)) {
            throw new \RuntimeException('Another game is already scheduled on this field at this time.');
        }

        return $this->repository->create($data);
    }

    public function update(Game $game, array $data): Game
    {
        return $this->repository->update($game, $data);
    }

    public function delete(Game $game): void
    {
        $this->repository->delete($game);
    }

    public function restore(Game $game): void
    {
        $this->repository->restore($game);
    }

    public function cancel(Game $game, int $cancellationReasonId): void
    {
        $this->repository->cancel($game, $cancellationReasonId);
    }
}
