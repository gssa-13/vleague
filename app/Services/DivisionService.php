<?php

// app/Services/DivisionService.php

namespace App\Services;

use App\Enums\DivisionDay;
use App\Models\Division;
use App\Repositories\DivisionRepository;
use Illuminate\Database\Eloquent\Collection;

class DivisionService
{
    public function __construct(
        private readonly DivisionRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Division
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Division
    {
        return $this->repository->findTrashedById($id);
    }

    /**
     * Generates the human-readable display name for a division.
     * Format: "{Day} {field_number}{group_letter?}"
     * Example: "Saturday 1A", "Monday 2"
     */
    public function generateDisplayName(DivisionDay $day, int $fieldNumber, ?string $groupLetter): string
    {
        return $day->label().' '.$fieldNumber.($groupLetter ?? '');
    }

    public function create(array $data): Division
    {
        $data['name'] = $this->generateDisplayName(
            DivisionDay::from($data['day']),
            $data['field_number'],
            $data['group_letter'] ?? null,
        );

        return $this->repository->create($data);
    }

    public function update(Division $division, array $data): Division
    {
        $data['name'] = $this->generateDisplayName(
            DivisionDay::from($data['day']),
            $data['field_number'],
            $data['group_letter'] ?? null,
        );

        return $this->repository->update($division, $data);
    }

    public function delete(Division $division): void
    {
        $this->repository->delete($division);
    }

    public function restore(Division $division): void
    {
        $this->repository->restore($division);
    }
}
