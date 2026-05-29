<?php

// app/Services/NavigationService.php

namespace App\Services;

use App\Models\NavigationItem;
use App\Repositories\NavigationRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class NavigationService
{
    public function __construct(
        private readonly NavigationRepository $repository,
    ) {}

    /**
     * Returns navigation items visible to the given user.
     * Items with a null permission_name are visible to all authenticated users.
     * Items with a permission_name are only visible if the user has that permission.
     */
    public function getVisibleItems(Authenticatable $user): Collection
    {
        $items = $this->repository->allActive();

        return $items->filter(function (NavigationItem $item) use ($user) {
            if ($item->permission_name === null) {
                return true;
            }

            return $user->hasPermissionTo($item->permission_name);
        })->values();
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?NavigationItem
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?NavigationItem
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): NavigationItem
    {
        return $this->repository->create($data);
    }

    public function update(NavigationItem $item, array $data): NavigationItem
    {
        return $this->repository->update($item, $data);
    }

    public function delete(NavigationItem $item): void
    {
        $this->repository->delete($item);
    }

    public function restore(NavigationItem $item): void
    {
        $this->repository->restore($item);
    }
}
