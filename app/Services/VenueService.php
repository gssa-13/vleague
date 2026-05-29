<?php

// app/Services/VenueService.php

namespace App\Services;

use App\Models\Venue;
use App\Repositories\VenueRepository;
use Illuminate\Database\Eloquent\Collection;

class VenueService
{
    public function __construct(
        private readonly VenueRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Venue
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Venue
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Venue
    {
        return $this->repository->create($data);
    }

    public function update(Venue $venue, array $data): Venue
    {
        return $this->repository->update($venue, $data);
    }

    public function delete(Venue $venue): void
    {
        $this->repository->delete($venue);
    }

    public function restore(Venue $venue): void
    {
        $this->repository->restore($venue);
    }
}
