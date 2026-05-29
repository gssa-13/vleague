<?php

// app/Services/TeamService.php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamRoster;
use App\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    public function __construct(
        private readonly TeamRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Team
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Team
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Team
    {
        return $this->repository->create($data);
    }

    public function update(Team $team, array $data): Team
    {
        return $this->repository->update($team, $data);
    }

    public function delete(Team $team): void
    {
        $this->repository->delete($team);
    }

    public function restore(Team $team): void
    {
        $this->repository->restore($team);
    }

    /**
     * Adds a player to a competition team roster.
     * Enforces a single captain per competition team.
     */
    public function addRosterEntry(int $competitionTeamId, array $data): TeamRoster
    {
        if (! empty($data['is_captain']) && $this->repository->hasCaptain($competitionTeamId)) {
            throw new \RuntimeException('This team already has a captain.');
        }

        return $this->repository->addRosterEntry($competitionTeamId, $data);
    }

    public function removeRosterEntry(TeamRoster $roster): void
    {
        $this->repository->removeRosterEntry($roster);
    }
}
