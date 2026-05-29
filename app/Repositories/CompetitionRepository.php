<?php

// app/Repositories/CompetitionRepository.php

namespace App\Repositories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Collection;

class CompetitionRepository
{
    public function all(): Collection
    {
        return Competition::with(['venue', 'tournament', 'division'])->get();
    }

    public function findById(int $id): ?Competition
    {
        return Competition::find($id);
    }

    public function findTrashedById(int $id): ?Competition
    {
        return Competition::withTrashed()->find($id);
    }

    public function existsActive(int $venueId, int $tournamentId, int $divisionId): bool
    {
        return Competition::where('venue_id', $venueId)
            ->where('tournament_id', $tournamentId)
            ->where('division_id', $divisionId)
            ->exists();
    }

    public function create(array $data): Competition
    {
        return Competition::create($data);
    }

    public function delete(Competition $competition): void
    {
        $competition->delete();
    }

    public function restore(Competition $competition): void
    {
        $competition->restore();
    }
}
