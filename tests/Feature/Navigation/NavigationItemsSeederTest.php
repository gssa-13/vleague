<?php

// tests/Feature/Navigation/NavigationItemsSeederTest.php

use App\Models\NavigationItem;
use App\Models\Permission;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\NavigationItemsSeeder;
use Illuminate\Support\Facades\Route;

it('creates the base navigation items', function () {
    $this->seed(NavigationItemsSeeder::class);

    expect(NavigationItem::where('label', 'Dashboard')->exists())->toBeTrue()
        ->and(NavigationItem::where('label', 'Operations')->exists())->toBeTrue()
        ->and(NavigationItem::where('label', 'Venues')->exists())->toBeTrue()
        ->and(NavigationItem::where('label', 'Players')->exists())->toBeTrue()
        ->and(NavigationItem::where('label', 'Payments')->exists())->toBeTrue()
        ->and(NavigationItem::where('label', 'Audit')->exists())->toBeTrue();
});

it('does not duplicate navigation items when seeded more than once', function () {
    $this->seed(NavigationItemsSeeder::class);
    $countAfterFirstRun = NavigationItem::count();

    $this->seed(NavigationItemsSeeder::class);

    expect(NavigationItem::count())->toBe($countAfterFirstRun);
});

it('uses only existing route names for seeded navigation items', function () {
    $this->seed(NavigationItemsSeeder::class);

    NavigationItem::whereNotNull('route_name')
        ->pluck('route_name')
        ->each(fn (string $routeName) => expect(Route::has($routeName))->toBeTrue());
});

it('uses only existing permissions or null permissions for seeded navigation items', function () {
    $this->seed(NavigationItemsSeeder::class);

    NavigationItem::whereNotNull('permission_name')
        ->pluck('permission_name')
        ->unique()
        ->each(fn (string $permissionName) => expect(Permission::where('name', $permissionName)->exists())->toBeTrue());
});

it('creates navigation items from the main database seeder', function () {
    $this->seed(DatabaseSeeder::class);

    expect(NavigationItem::where('label', 'Dashboard')->exists())->toBeTrue()
        ->and(NavigationItem::where('label', 'Audit')->exists())->toBeTrue();
});

it('can run the main database seeder more than once', function () {
    $this->seed(DatabaseSeeder::class);
    $navigationCount = NavigationItem::count();

    $this->seed(DatabaseSeeder::class);

    expect(NavigationItem::count())->toBe($navigationCount);
});
