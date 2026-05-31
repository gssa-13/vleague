<?php

// app/Services/NavigationService.php

namespace App\Services;

use App\Models\NavigationItem;
use App\Repositories\NavigationRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;

class NavigationService
{
    public function __construct(
        private readonly NavigationRepository $repository,
    ) {}

    /**
     * Returns navigation items visible to the given user.
     * Items with a null permission_name are visible to all authenticated users.
     * Items with a permission_name are only visible if the user can pass that ability.
     */
    public function getVisibleItems(Authenticatable $user): Collection
    {
        $items = $this->repository->allActive();

        return $items->filter(fn (NavigationItem $item) => $this->canViewItem($item, $user))->values();
    }

    public function getVisibleTree(Authenticatable $user): Collection
    {
        $items = $this->repository->topLevelActiveWithChildren();
        $visibleItems = $items
            ->map(fn (NavigationItem $item) => $this->filterVisibleTreeItem($item, $user))
            ->filter()
            ->values();

        return new Collection($visibleItems->all());
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

    private function filterVisibleTreeItem(NavigationItem $item, Authenticatable $user): ?NavigationItem
    {
        if (! $this->canViewItem($item, $user)) {
            return null;
        }

        if (! $this->hasValidRoute($item)) {
            return null;
        }

        $visibleChildren = $item->children
            ->map(fn (NavigationItem $child) => $this->filterVisibleTreeItem($child, $user))
            ->filter()
            ->values();

        $item->setRelation('children', new Collection($visibleChildren->all()));

        if ($item->route_name === null && $item->children->isEmpty()) {
            return null;
        }

        return $item;
    }

    private function canViewItem(NavigationItem $item, Authenticatable $user): bool
    {
        if ($item->permission_name === null) {
            return true;
        }

        return $user->can($item->permission_name);
    }

    private function hasValidRoute(NavigationItem $item): bool
    {
        return $item->route_name === null || Route::has($item->route_name);
    }
}
