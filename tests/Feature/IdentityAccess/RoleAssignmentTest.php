<?php

// tests/Feature/IdentityAccess/RoleAssignmentTest.php

use App\Models\Role;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

it('assigns a role to a user', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.assign');

    $target = User::factory()->create();
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

    $this->actingAs($actor)
        ->post(route('users.roles.assign', $target), ['role' => 'staff'])
        ->assertRedirect();

    expect($target->fresh()->hasRole('staff'))->toBeTrue();
});

it('revokes a role from a user', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('roles.assign');

    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
    $target = User::factory()->create();
    $target->assignRole('staff');

    $this->actingAs($actor)
        ->delete(route('users.roles.revoke', [$target, 'staff']))
        ->assertRedirect();

    expect($target->fresh()->hasRole('staff'))->toBeFalse();
});

it('assigns a direct permission to a user', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('permissions.assign');

    $target = User::factory()->create();

    $this->actingAs($actor)
        ->post(route('users.permissions.assign', $target), ['permission' => 'venues.view'])
        ->assertRedirect();

    expect($target->fresh()->hasDirectPermission('venues.view'))->toBeTrue();
});

it('user with a soft-deleted role loses that role permissions', function () {
    $role = Role::create(['name' => 'temp-role', 'guard_name' => 'web']);
    $role->givePermissionTo('venues.view');

    $user = User::factory()->create();
    $user->assignRole('temp-role');

    expect($user->hasPermissionTo('venues.view'))->toBeTrue();

    $role->delete();

    // Spatie caches permissions — clear cache before re-checking
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    expect($user->fresh()->hasPermissionTo('venues.view'))->toBeFalse();
});
