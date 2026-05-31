<?php

// app/Repositories/NavigationRepository.php

namespace App\Repositories;

use App\Models\NavigationItem;
use Illuminate\Database\Eloquent\Collection;

class NavigationRepository
{
    public function allActive(): Collection
    {
        return NavigationItem::orderBy('sort_order')->get();
    }

    public function topLevelActiveWithChildren(): Collection
    {
        return NavigationItem::with([
            'children' => fn ($query) => $query->orderBy('sort_order'),
        ])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
    }

    public function all(): Collection
    {
        return NavigationItem::orderBy('sort_order')->get();
    }

    public function findById(int $id): ?NavigationItem
    {
        return NavigationItem::find($id);
    }

    public function findTrashedById(int $id): ?NavigationItem
    {
        return NavigationItem::withTrashed()->find($id);
    }

    public function create(array $data): NavigationItem
    {
        return NavigationItem::create($data);
    }

    public function update(NavigationItem $item, array $data): NavigationItem
    {
        $item->update($data);

        return $item;
    }

    public function delete(NavigationItem $item): void
    {
        $item->delete();
    }

    public function restore(NavigationItem $item): void
    {
        $item->restore();
    }
}
