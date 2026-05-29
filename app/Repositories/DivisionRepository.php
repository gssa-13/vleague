<?php

// app/Repositories/DivisionRepository.php

namespace App\Repositories;

use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;

class DivisionRepository
{
    public function all(): Collection
    {
        return Division::with('tournament')->get();
    }

    public function findById(int $id): ?Division
    {
        return Division::find($id);
    }

    public function findTrashedById(int $id): ?Division
    {
        return Division::withTrashed()->find($id);
    }

    public function create(array $data): Division
    {
        return Division::create($data);
    }

    public function update(Division $division, array $data): Division
    {
        $division->update($data);

        return $division;
    }

    public function delete(Division $division): void
    {
        $division->delete();
    }

    public function restore(Division $division): void
    {
        $division->restore();
    }
}
