<?php

// tests/Feature/Navigation/GlobalNavigationMenuTest.php

use App\Models\NavigationItem;
use App\Models\Role;
use App\Models\User;

it('shows public navigation items in desktop and mobile menus', function () {
    $user = User::factory()->create();

    NavigationItem::factory()->create([
        'label' => 'Public Portal',
        'route_name' => 'dashboard',
        'permission_name' => null,
        'sort_order' => 1,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Public Portal');

    expect(substr_count($response->getContent(), 'Public Portal'))
        ->toBeGreaterThanOrEqual(2);
});

it('shows protected navigation items when the user has the required permission', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('venues.view');

    $group = NavigationItem::factory()->create([
        'label' => 'Operations Group',
        'route_name' => null,
        'permission_name' => null,
        'sort_order' => 10,
    ]);

    NavigationItem::factory()->create([
        'label' => 'Venues Menu',
        'route_name' => 'venues.index',
        'permission_name' => 'venues.view',
        'parent_id' => $group->id,
        'sort_order' => 11,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Operations Group')
        ->assertSee('Venues Menu');

    expect(substr_count($response->getContent(), 'Venues Menu'))
        ->toBeGreaterThanOrEqual(2);
});

it('hides protected navigation items and empty groups when the user lacks permission', function () {
    $user = User::factory()->create();

    $group = NavigationItem::factory()->create([
        'label' => 'Restricted Group',
        'route_name' => null,
        'permission_name' => null,
        'sort_order' => 10,
    ]);

    NavigationItem::factory()->create([
        'label' => 'Restricted Venues',
        'route_name' => 'venues.index',
        'permission_name' => 'venues.view',
        'parent_id' => $group->id,
        'sort_order' => 11,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Restricted Group')
        ->assertDontSee('Restricted Venues');
});

it('shows only the permitted child items for partially authorized users', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('audit.view');

    $group = NavigationItem::factory()->create([
        'label' => 'Administration Group',
        'route_name' => null,
        'permission_name' => null,
        'sort_order' => 20,
    ]);

    NavigationItem::factory()->create([
        'label' => 'Users Menu',
        'route_name' => 'users.index',
        'permission_name' => 'users.view',
        'parent_id' => $group->id,
        'sort_order' => 21,
    ]);

    NavigationItem::factory()->create([
        'label' => 'Audit Menu',
        'route_name' => 'audit.index',
        'permission_name' => 'audit.view',
        'parent_id' => $group->id,
        'sort_order' => 22,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Administration Group')
        ->assertSee('Audit Menu')
        ->assertDontSee('Users Menu');
});

it('shows protected navigation items for super admin users', function () {
    $user = User::factory()->create();
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    $user->assignRole('super-admin');

    $group = NavigationItem::factory()->create([
        'label' => 'Identity Group',
        'route_name' => null,
        'permission_name' => null,
        'sort_order' => 30,
    ]);

    NavigationItem::factory()->create([
        'label' => 'Permissions Menu',
        'route_name' => 'permissions.index',
        'permission_name' => 'permissions.view',
        'parent_id' => $group->id,
        'sort_order' => 31,
    ]);

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertOk()
        ->assertSee('Identity Group')
        ->assertSee('Permissions Menu');
});
