<?php

// app/Repositories/VenueRepository.php

namespace App\Repositories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Collection;

class VenueRepository
{
    public function all(): Collection
    {
        return Venue::all();
    }

    public function findById(int $id): ?Venue
    {
        return Venue::find($id);
    }

    public function findTrashedById(int $id): ?Venue
    {
        return Venue::withTrashed()->find($id);
    }

    public function create(array $data): Venue
    {
        return Venue::create($data);
    }

    public function update(Venue $venue, array $data): Venue
    {
        $venue->update($data);

        return $venue;
    }

    public function delete(Venue $venue): void
    {
        $venue->delete();
    }

    public function restore(Venue $venue): void
    {
        $venue->restore();
    }
}
