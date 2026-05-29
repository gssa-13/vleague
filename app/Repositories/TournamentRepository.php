<?php

// app/Repositories/TournamentRepository.php

namespace App\Repositories;

use App\Enums\TournamentStatus;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Collection;

class TournamentRepository
{
    public function all(): Collection
    {
        return Tournament::with('venue')->get();
    }

    public function findById(int $id): ?Tournament
    {
        return Tournament::find($id);
    }

    public function findTrashedById(int $id): ?Tournament
    {
        return Tournament::withTrashed()->find($id);
    }

    public function findActiveByVenue(int $venueId): Collection
    {
        return Tournament::where('venue_id', $venueId)
            ->where('status', TournamentStatus::Active)
            ->get();
    }

    public function create(array $data): Tournament
    {
        return Tournament::create($data);
    }

    public function update(Tournament $tournament, array $data): Tournament
    {
        $tournament->update($data);

        return $tournament;
    }

    public function delete(Tournament $tournament): void
    {
        $tournament->delete();
    }

    public function restore(Tournament $tournament): void
    {
        $tournament->restore();
    }
}
