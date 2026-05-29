<?php

// tests/Feature/IdentityAccess/UserCrudTest.php

use App\Models\ActivityLog;
use App\Models\User;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from users index', function () {
    $response = $this->get(route('users.index'));

    $response->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks users.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

it('lists users when user has users.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('users.view');

    User::factory()->count(3)->create();

    $this->actingAs($viewer)
        ->get(route('users.index'))
        ->assertOk()
        ->assertViewIs('identity-access.users.index');
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a user when actor has users.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.create');

    $this->actingAs($actor)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
});

it('fails validation when creating user with duplicate email', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.create');

    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($actor)
        ->post(route('users.store'), [
            'name' => 'Duplicate',
            'email' => 'taken@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertSessionHasErrors('email');
});

it('fails validation when creating user with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.create');

    $this->actingAs($actor)
        ->post(route('users.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

// ─── Update ──────────────────────────────────────────────────────────────────

it('updates a user when actor has users.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.update');

    $target = User::factory()->create(['name' => 'Old Name']);

    $this->actingAs($actor)
        ->put(route('users.update', $target), [
            'name' => 'New Name',
            'email' => $target->email,
        ])
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => 'New Name']);
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a user when actor has users.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.delete');

    $target = User::factory()->create();

    $this->actingAs($actor)
        ->delete(route('users.destroy', $target))
        ->assertRedirect(route('users.index'));

    $this->assertSoftDeleted('users', ['id' => $target->id]);
});

it('excludes soft-deleted users from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('users.view');

    $deleted = User::factory()->create(['name' => 'Deleted User']);
    $deleted->delete();

    $response = $this->actingAs($viewer)->get(route('users.index'));

    $response->assertOk();
    $users = $response->viewData('users');
    expect($users->contains('id', $deleted->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted user when actor has users.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.restore');

    $target = User::factory()->create();
    $target->delete();

    $this->actingAs($actor)
        ->post(route('users.restore', $target->id))
        ->assertRedirect(route('users.index'));

    $this->assertNotSoftDeleted('users', ['id' => $target->id]);
});

// ─── Audit ───────────────────────────────────────────────────────────────────

it('records an activity log entry when a user is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.create');

    $this->actingAs($actor)
        ->post(route('users.store'), [
            'name' => 'Audited User',
            'email' => 'audited@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $created = User::where('email', 'audited@example.com')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', User::class)
            ->where('record_id', $created->id)
            ->exists()
    )->toBeTrue();
});

it('records an activity log entry when a user is soft deleted', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.delete');

    $target = User::factory()->create();

    $this->actingAs($actor)
        ->delete(route('users.destroy', $target));

    expect(
        ActivityLog::where('action', 'deleted')
            ->where('model', User::class)
            ->where('record_id', $target->id)
            ->exists()
    )->toBeTrue();
});

it('records an activity log entry when a user is restored', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('users.restore');

    $target = User::factory()->create();
    $target->delete();
    ActivityLog::query()->delete();

    $this->actingAs($actor)
        ->post(route('users.restore', $target->id));

    expect(
        ActivityLog::where('action', 'restored')
            ->where('model', User::class)
            ->where('record_id', $target->id)
            ->exists()
    )->toBeTrue();
});
