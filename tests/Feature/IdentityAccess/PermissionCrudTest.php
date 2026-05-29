<?php

// tests/Feature/IdentityAccess/PermissionCrudTest.php

use App\Models\Permission;
use App\Models\User;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from permissions index', function () {
    $this->get(route('permissions.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks permissions.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertForbidden();
});

it('lists permissions when user has permissions.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('permissions.view');

    $this->actingAs($viewer)
        ->get(route('permissions.index'))
        ->assertOk()
        ->assertViewIs('identity-access.permissions.index');
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a permission when actor has permissions.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('permissions.delete');

    $permission = Permission::create(['name' => 'test.delete-me', 'guard_name' => 'web']);

    $this->actingAs($actor)
        ->delete(route('permissions.destroy', $permission))
        ->assertRedirect(route('permissions.index'));

    $this->assertSoftDeleted('permissions', ['id' => $permission->id]);
});

it('excludes soft-deleted permissions from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('permissions.view');

    $permission = Permission::create(['name' => 'test.ghost', 'guard_name' => 'web']);
    $permission->delete();

    $response = $this->actingAs($viewer)->get(route('permissions.index'));

    $response->assertOk();
    $permissions = $response->viewData('permissions');
    expect($permissions->contains('id', $permission->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted permission when actor has permissions.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('permissions.restore');

    $permission = Permission::create(['name' => 'test.restorable', 'guard_name' => 'web']);
    $permission->delete();

    $this->actingAs($actor)
        ->post(route('permissions.restore', $permission->id))
        ->assertRedirect(route('permissions.index'));

    $this->assertNotSoftDeleted('permissions', ['id' => $permission->id]);
});
