<?php

// app/Repositories/TeamRepository.php

namespace App\Repositories;

use App\Models\Team;
use App\Models\TeamRoster;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    public function all(): Collection
    {
        return Team::all();
    }

    public function findById(int $id): ?Team
    {
        return Team::find($id);
    }

    public function findTrashedById(int $id): ?Team
    {
        return Team::withTrashed()->find($id);
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function update(Team $team, array $data): Team
    {
        $team->update($data);

        return $team;
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }

    public function restore(Team $team): void
    {
        $team->restore();
    }

    public function hasCaptain(int $competitionTeamId): bool
    {
        return TeamRoster::where('competition_team_id', $competitionTeamId)
            ->where('is_captain', true)
            ->exists();
    }

    public function addRosterEntry(int $competitionTeamId, array $data): TeamRoster
    {
        $data['competition_team_id'] = $competitionTeamId;

        return TeamRoster::create($data);
    }

    public function removeRosterEntry(TeamRoster $roster): void
    {
        $roster->delete();
    }
}
