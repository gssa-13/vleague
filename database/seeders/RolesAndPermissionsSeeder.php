<?php

// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeds the base roles and permissions for vl League.
 *
 * Permissions follow the pattern: {module}.{action}
 * Roles are created with named slugs — never hardcoded IDs.
 *
 * This seeder is idempotent: running it multiple times produces the same result.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * All base permissions grouped by module.
     * Pattern: {module}.{action}
     */
    private const PERMISSIONS = [
        // Identity & Access
        'users.view',
        'users.create',
        'users.update',
        'users.delete',
        'users.restore',

        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',
        'roles.restore',
        'roles.assign',

        'permissions.view',
        'permissions.create',
        'permissions.update',
        'permissions.delete',
        'permissions.restore',
        'permissions.assign',

        // Navigation
        'navigation.view',
        'navigation.create',
        'navigation.update',
        'navigation.delete',
        'navigation.restore',

        // Venues
        'venues.view',
        'venues.create',
        'venues.update',
        'venues.delete',
        'venues.restore',

        // Tournaments
        'tournaments.view',
        'tournaments.create',
        'tournaments.update',
        'tournaments.delete',
        'tournaments.restore',

        // Divisions
        'divisions.view',
        'divisions.create',
        'divisions.update',
        'divisions.delete',
        'divisions.restore',

        // Competitions
        'competitions.view',
        'competitions.create',
        'competitions.update',
        'competitions.delete',
        'competitions.restore',
        'competitions.assign',

        // Prices & Categories
        'prices.view',
        'prices.create',
        'prices.update',
        'prices.delete',
        'prices.restore',

        'categories.view',
        'categories.create',
        'categories.update',
        'categories.delete',
        'categories.restore',

        // Employees
        'employees.view',
        'employees.create',
        'employees.update',
        'employees.delete',
        'employees.restore',

        // Players
        'players.view',
        'players.create',
        'players.update',
        'players.delete',
        'players.restore',
        'players.sanction',

        // Teams
        'teams.view',
        'teams.create',
        'teams.update',
        'teams.delete',
        'teams.restore',
        'teams.assign',

        // Player Sanctions
        'player-sanctions.view',
        'player-sanctions.create',
        'player-sanctions.update',
        'player-sanctions.delete',
        'player-sanctions.restore',
        'player-sanctions.cancel',

        // Scheduling / Games
        'games.view',
        'games.create',
        'games.update',
        'games.delete',
        'games.restore',
        'games.cancel',
        'games.results.update',

        // Match Roles
        'game-roles.view',
        'game-roles.create',
        'game-roles.update',
        'game-roles.delete',
        'game-roles.restore',
        'game-roles.assign',

        // Finance
        'payments.view',
        'payments.create',
        'payments.update',
        'payments.delete',
        'payments.restore',
        'payments.cancel',
        'payments.approve',
        'payments.export',

        // Payroll
        'payroll.view',
        'payroll.create',
        'payroll.update',
        'payroll.delete',
        'payroll.restore',
        'payroll.approve',
        'payroll.cancel',

        // Media
        'media.view',
        'media.create',
        'media.update',
        'media.delete',
        'media.restore',

        // Reports
        'reports.view',
        'reports.export',

        // Audit
        'audit.view',
        'audit.export',
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions idempotently
        foreach (self::PERMISSIONS as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        // Create super-admin role — bypasses all Gates via AppServiceProvider
        Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );

        // Create admin role with all permissions
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $adminRole->syncPermissions(self::PERMISSIONS);

        // Create staff role with read-only access to most modules
        $staffRole = Role::firstOrCreate(
            ['name' => 'staff', 'guard_name' => 'web']
        );
        $staffRole->syncPermissions(array_filter(
            self::PERMISSIONS,
            fn (string $p) => str_ends_with($p, '.view')
        ));
    }
}
