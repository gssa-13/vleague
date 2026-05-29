<?php

// tests/Feature/IdentityAccess/RoleCrudTest.php

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from roles index', function () {
    $this->get(route('roles.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks roles.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertForbidden();
});

it('lists roles when user has roles.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('roles.view');

    $this->actingAs($viewer)
        ->get(route('roles.index'))
        ->assertOk()
        ->assertViewIs('identity-access.roles.index');
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a role when actor has roles.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.create');

    $this->actingAs($actor)
        ->post(route('roles.store'), ['name' => 'editor'])
        ->assertRedirect(route('roles.index'));

    $this->assertDatabaseHas('roles', ['name' => 'editor']);
});

it('fails validation when creating role with duplicate name', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.create');

    Role::create(['name' => 'existing-role', 'guard_name' => 'web']);

    $this->actingAs($actor)
        ->post(route('roles.store'), ['name' => 'existing-role'])
        ->assertSessionHasErrors('name');
});

it('fails validation when creating role with missing name', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.create');

    $this->actingAs($actor)
        ->post(route('roles.store'), [])
        ->assertSessionHasErrors('name');
});

// ─── Update ──────────────────────────────────────────────────────────────────

it('updates a role when actor has roles.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.update');

    $role = Role::create(['name' => 'old-role', 'guard_name' => 'web']);

    $this->actingAs($actor)
        ->put(route('roles.update', $role), ['name' => 'new-role'])
        ->assertRedirect(route('roles.index'));

    $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'new-role']);
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a role when actor has roles.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.delete');

    $role = Role::create(['name' => 'deletable-role', 'guard_name' => 'web']);

    $this->actingAs($actor)
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'));

    $this->assertSoftDeleted('roles', ['id' => $role->id]);
});

it('excludes soft-deleted roles from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('roles.view');

    $role = Role::create(['name' => 'ghost-role', 'guard_name' => 'web']);
    $role->delete();

    $response = $this->actingAs($viewer)->get(route('roles.index'));

    $response->assertOk();
    $roles = $response->viewData('roles');
    expect($roles->contains('id', $role->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted role when actor has roles.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.restore');

    $role = Role::create(['name' => 'restorable-role', 'guard_name' => 'web']);
    $role->delete();

    $this->actingAs($actor)
        ->post(route('roles.restore', $role->id))
        ->assertRedirect(route('roles.index'));

    $this->assertNotSoftDeleted('roles', ['id' => $role->id]);
});

// ─── Audit ───────────────────────────────────────────────────────────────────

it('records an activity log entry when a role is created via controller', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.create');

    $this->actingAs($actor)
        ->post(route('roles.store'), ['name' => 'logged-role']);

    $created = Role::where('name', 'logged-role')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Role::class)
            ->where('record_id', $created->id)
            ->exists()
    )->toBeTrue();
});

it('records an activity log entry when a role is soft deleted via controller', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.delete');

    $role = Role::create(['name' => 'to-delete', 'guard_name' => 'web']);

    $this->actingAs($actor)
        ->delete(route('roles.destroy', $role));

    expect(
        ActivityLog::where('action', 'deleted')
            ->where('model', Role::class)
            ->where('record_id', $role->id)
            ->exists()
    )->toBeTrue();
});

it('records an activity log entry when a role is restored via controller', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.restore');

    $role = Role::create(['name' => 'to-restore', 'guard_name' => 'web']);
    $role->delete();
    ActivityLog::query()->delete();

    $this->actingAs($actor)
        ->post(route('roles.restore', $role->id));

    expect(
        ActivityLog::where('action', 'restored')
            ->where('model', Role::class)
            ->where('record_id', $role->id)
            ->exists()
    )->toBeTrue();
});
