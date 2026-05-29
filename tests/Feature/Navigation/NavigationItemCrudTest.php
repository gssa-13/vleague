<?php

// tests/Feature/Navigation/NavigationItemCrudTest.php

use App\Models\NavigationItem;
use App\Models\User;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from navigation index', function () {
    $this->get(route('navigation.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks navigation.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('navigation.index'))
        ->assertForbidden();
});

it('lists navigation items when user has navigation.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('navigation.view');

    NavigationItem::factory()->count(3)->create();

    $this->actingAs($viewer)
        ->get(route('navigation.index'))
        ->assertOk()
        ->assertViewIs('navigation.index');
});

// ─── Visibility rules ────────────────────────────────────────────────────────

it('shows item with null permission_name to all authenticated users', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('navigation.view');

    $item = NavigationItem::factory()->create(['permission_name' => null]);

    $response = $this->actingAs($user)->get(route('navigation.index'));

    $response->assertOk();
    $items = $response->viewData('items');
    expect($items->contains('id', $item->id))->toBeTrue();
});

it('shows item with permission_name only to users who have that permission', function () {
    $withPerm = User::factory()->create();
    $withoutPerm = User::factory()->create();

    $withPerm->givePermissionTo(['navigation.view', 'venues.view']);
    $withoutPerm->givePermissionTo('navigation.view');

    $item = NavigationItem::factory()->create(['permission_name' => 'venues.view']);

    $responseWith = $this->actingAs($withPerm)->get(route('navigation.index'));
    $responseWithout = $this->actingAs($withoutPerm)->get(route('navigation.index'));

    expect($responseWith->viewData('items')->contains('id', $item->id))->toBeTrue();
    expect($responseWithout->viewData('items')->contains('id', $item->id))->toBeFalse();
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a navigation item when actor has navigation.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('navigation.create');

    $this->actingAs($actor)
        ->post(route('navigation.store'), [
            'label' => 'Venues',
            'route_name' => 'venues.index',
            'icon' => 'fas fa-building',
            'permission_name' => null,
            'sort_order' => 1,
        ])
        ->assertRedirect(route('navigation.index'));

    $this->assertDatabaseHas('navigation_items', ['label' => 'Venues']);
});

// ─── Update ──────────────────────────────────────────────────────────────────

it('updates a navigation item when actor has navigation.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('navigation.update');

    $item = NavigationItem::factory()->create(['label' => 'Old Label']);

    $this->actingAs($actor)
        ->put(route('navigation.update', $item), [
            'label' => 'New Label',
            'route_name' => $item->route_name,
            'sort_order' => $item->sort_order,
        ])
        ->assertRedirect(route('navigation.index'));

    $this->assertDatabaseHas('navigation_items', ['id' => $item->id, 'label' => 'New Label']);
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a navigation item when actor has navigation.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('navigation.delete');

    $item = NavigationItem::factory()->create();

    $this->actingAs($actor)
        ->delete(route('navigation.destroy', $item))
        ->assertRedirect(route('navigation.index'));

    $this->assertSoftDeleted('navigation_items', ['id' => $item->id]);
});

it('excludes soft-deleted items from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('navigation.view');

    $item = NavigationItem::factory()->create();
    $item->delete();

    $response = $this->actingAs($viewer)->get(route('navigation.index'));

    $response->assertOk();
    $items = $response->viewData('items');
    expect($items->contains('id', $item->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted navigation item when actor has navigation.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('navigation.restore');

    $item = NavigationItem::factory()->create();
    $item->delete();

    $this->actingAs($actor)
        ->post(route('navigation.restore', $item->id))
        ->assertRedirect(route('navigation.index'));

    $this->assertNotSoftDeleted('navigation_items', ['id' => $item->id]);
});
