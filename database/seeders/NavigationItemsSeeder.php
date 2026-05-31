<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationItemsSeeder extends Seeder
{
    private const ITEMS = [
        ['label' => 'Dashboard', 'label_key' => 'navigation.dashboard', 'route_name' => 'dashboard', 'permission_name' => null, 'sort_order' => 1],
        ['label' => 'Operations', 'label_key' => 'navigation.operations', 'route_name' => null, 'permission_name' => null, 'sort_order' => 10, 'children' => [
            ['label' => 'Venues', 'label_key' => 'navigation.venues', 'route_name' => 'venues.index', 'permission_name' => 'venues.view', 'sort_order' => 11],
            ['label' => 'Tournaments', 'label_key' => 'navigation.tournaments', 'route_name' => 'tournaments.index', 'permission_name' => 'tournaments.view', 'sort_order' => 12],
            ['label' => 'Divisions', 'label_key' => 'navigation.divisions', 'route_name' => 'divisions.index', 'permission_name' => 'divisions.view', 'sort_order' => 13],
            ['label' => 'Competitions', 'label_key' => 'navigation.competitions', 'route_name' => 'competitions.index', 'permission_name' => 'competitions.view', 'sort_order' => 14],
        ]],
        ['label' => 'People', 'label_key' => 'navigation.people', 'route_name' => null, 'permission_name' => null, 'sort_order' => 20, 'children' => [
            ['label' => 'Employees', 'label_key' => 'navigation.employees', 'route_name' => 'employees.index', 'permission_name' => 'employees.view', 'sort_order' => 21],
            ['label' => 'Players', 'label_key' => 'navigation.players', 'route_name' => 'players.index', 'permission_name' => 'players.view', 'sort_order' => 22],
            ['label' => 'Teams', 'label_key' => 'navigation.teams', 'route_name' => 'teams.index', 'permission_name' => 'teams.view', 'sort_order' => 23],
            ['label' => 'Player Sanctions', 'label_key' => 'navigation.player_sanctions', 'route_name' => 'player-sanctions.index', 'permission_name' => 'player-sanctions.view', 'sort_order' => 24],
        ]],
        ['label' => 'Games', 'label_key' => 'navigation.games', 'route_name' => null, 'permission_name' => null, 'sort_order' => 30, 'children' => [
            ['label' => 'Scheduling', 'label_key' => 'navigation.scheduling', 'route_name' => 'games.index', 'permission_name' => 'games.view', 'sort_order' => 31],
            ['label' => 'Game Roles', 'label_key' => 'navigation.game_roles', 'route_name' => 'game-roles.index', 'permission_name' => 'game-roles.view', 'sort_order' => 32],
        ]],
        ['label' => 'Finance', 'label_key' => 'navigation.finance', 'route_name' => null, 'permission_name' => null, 'sort_order' => 40, 'children' => [
            ['label' => 'Payments', 'label_key' => 'navigation.payments', 'route_name' => 'payments.index', 'permission_name' => 'payments.view', 'sort_order' => 41],
            ['label' => 'Payroll', 'label_key' => 'navigation.payroll', 'route_name' => 'payrolls.index', 'permission_name' => 'payroll.view', 'sort_order' => 42],
            ['label' => 'Prices', 'label_key' => 'navigation.prices', 'route_name' => 'prices.index', 'permission_name' => 'prices.view', 'sort_order' => 43],
            ['label' => 'Categories', 'label_key' => 'navigation.categories', 'route_name' => 'categories.index', 'permission_name' => 'categories.view', 'sort_order' => 44],
            ['label' => 'Reports', 'label_key' => 'navigation.reports', 'route_name' => 'reports.financial.index', 'permission_name' => 'reports.view', 'sort_order' => 45],
        ]],
        ['label' => 'Administration', 'label_key' => 'navigation.administration', 'route_name' => null, 'permission_name' => null, 'sort_order' => 50, 'children' => [
            ['label' => 'Navigation', 'label_key' => 'navigation.navigation', 'route_name' => 'navigation.index', 'permission_name' => 'navigation.view', 'sort_order' => 51],
            ['label' => 'Users', 'label_key' => 'navigation.users', 'route_name' => 'users.index', 'permission_name' => 'users.view', 'sort_order' => 52],
            ['label' => 'Roles', 'label_key' => 'navigation.roles', 'route_name' => 'roles.index', 'permission_name' => 'roles.view', 'sort_order' => 53],
            ['label' => 'Permissions', 'label_key' => 'navigation.permissions', 'route_name' => 'permissions.index', 'permission_name' => 'permissions.view', 'sort_order' => 54],
            ['label' => 'Media', 'label_key' => 'navigation.media', 'route_name' => 'media.index', 'permission_name' => 'media.view', 'sort_order' => 55],
            ['label' => 'Audit', 'label_key' => 'navigation.audit', 'route_name' => 'audit.index', 'permission_name' => 'audit.view', 'sort_order' => 56],
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
                'label_key' => $item['label_key'],
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
