<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationItemsSeeder extends Seeder
{
    private const ITEMS = [
        ['label' => 'Dashboard', 'route_name' => 'dashboard', 'permission_name' => null, 'sort_order' => 1],
        ['label' => 'Operations', 'route_name' => null, 'permission_name' => null, 'sort_order' => 10, 'children' => [
            ['label' => 'Venues', 'route_name' => 'venues.index', 'permission_name' => 'venues.view', 'sort_order' => 11],
            ['label' => 'Tournaments', 'route_name' => 'tournaments.index', 'permission_name' => 'tournaments.view', 'sort_order' => 12],
            ['label' => 'Divisions', 'route_name' => 'divisions.index', 'permission_name' => 'divisions.view', 'sort_order' => 13],
            ['label' => 'Competitions', 'route_name' => 'competitions.index', 'permission_name' => 'competitions.view', 'sort_order' => 14],
        ]],
        ['label' => 'People', 'route_name' => null, 'permission_name' => null, 'sort_order' => 20, 'children' => [
            ['label' => 'Employees', 'route_name' => 'employees.index', 'permission_name' => 'employees.view', 'sort_order' => 21],
            ['label' => 'Players', 'route_name' => 'players.index', 'permission_name' => 'players.view', 'sort_order' => 22],
            ['label' => 'Teams', 'route_name' => 'teams.index', 'permission_name' => 'teams.view', 'sort_order' => 23],
            ['label' => 'Player Sanctions', 'route_name' => 'player-sanctions.index', 'permission_name' => 'player-sanctions.view', 'sort_order' => 24],
        ]],
        ['label' => 'Games', 'route_name' => null, 'permission_name' => null, 'sort_order' => 30, 'children' => [
            ['label' => 'Scheduling', 'route_name' => 'games.index', 'permission_name' => 'games.view', 'sort_order' => 31],
            ['label' => 'Game Roles', 'route_name' => 'game-roles.index', 'permission_name' => 'game-roles.view', 'sort_order' => 32],
        ]],
        ['label' => 'Finance', 'route_name' => null, 'permission_name' => null, 'sort_order' => 40, 'children' => [
            ['label' => 'Payments', 'route_name' => 'payments.index', 'permission_name' => 'payments.view', 'sort_order' => 41],
            ['label' => 'Payroll', 'route_name' => 'payrolls.index', 'permission_name' => 'payroll.view', 'sort_order' => 42],
            ['label' => 'Prices', 'route_name' => 'prices.index', 'permission_name' => 'prices.view', 'sort_order' => 43],
            ['label' => 'Categories', 'route_name' => 'categories.index', 'permission_name' => 'categories.view', 'sort_order' => 44],
            ['label' => 'Reports', 'route_name' => 'reports.financial.index', 'permission_name' => 'reports.view', 'sort_order' => 45],
        ]],
        ['label' => 'Administration', 'route_name' => null, 'permission_name' => null, 'sort_order' => 50, 'children' => [
            ['label' => 'Navigation', 'route_name' => 'navigation.index', 'permission_name' => 'navigation.view', 'sort_order' => 51],
            ['label' => 'Users', 'route_name' => 'users.index', 'permission_name' => 'users.view', 'sort_order' => 52],
            ['label' => 'Roles', 'route_name' => 'roles.index', 'permission_name' => 'roles.view', 'sort_order' => 53],
            ['label' => 'Permissions', 'route_name' => 'permissions.index', 'permission_name' => 'permissions.view', 'sort_order' => 54],
            ['label' => 'Media', 'route_name' => 'media.index', 'permission_name' => 'media.view', 'sort_order' => 55],
            ['label' => 'Audit', 'route_name' => 'audit.index', 'permission_name' => 'audit.view', 'sort_order' => 56],
        ]],
    ];

    public function run(): void
    {
        foreach (self::ITEMS as $item) {
            $this->syncItem($item);
        }
    }

    private function syncItem(array $item, ?NavigationItem $parent = null): NavigationItem
    {
        $navigationItem = NavigationItem::withTrashed()->updateOrCreate(
            ['label' => $item['label'], 'route_name' => $item['route_name']],
            [
                'permission_name' => $item['permission_name'],
                'parent_id' => $parent?->id,
                'sort_order' => $item['sort_order'],
            ],
        );

        if ($navigationItem->trashed()) {
            $navigationItem->restore();
        }

        foreach ($item['children'] ?? [] as $child) {
            $this->syncItem($child, $navigationItem);
        }

        return $navigationItem;
    }
}
