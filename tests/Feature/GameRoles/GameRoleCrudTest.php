<?php

// tests/Feature/GameRoles/GameRoleCrudTest.php

use App\Models\ActivityLog;
use App\Models\GameRole;
use App\Models\User;

it('redirects unauthenticated user away from game roles index', function () {
    $this->get(route('game-roles.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks game-roles.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('game-roles.index'))
        ->assertForbidden();
});

it('lists game roles when user has game-roles.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('game-roles.view');

    GameRole::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('game-roles.index'))
        ->assertOk()
        ->assertViewIs('game-roles.index');
});

it('creates a game role when actor has game-roles.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.create');

    $this->actingAs($actor)
        ->post(route('game-roles.store'), [
            'name' => 'Referee',
            'description' => 'Main match referee',
        ])
        ->assertRedirect(route('game-roles.index'));

    $this->assertDatabaseHas('game_roles', ['name' => 'Referee']);
});

it('fails validation when creating game role with missing name', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.create');

    $this->actingAs($actor)
        ->post(route('game-roles.store'), [])
        ->assertSessionHasErrors('name');
});

it('updates a game role when actor has game-roles.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.update');

    $gameRole = GameRole::factory()->create(['name' => 'Old Role']);

    $this->actingAs($actor)
        ->put(route('game-roles.update', $gameRole), [
            'name' => 'New Role',
            'description' => $gameRole->description,
        ])
        ->assertRedirect(route('game-roles.index'));

    $this->assertDatabaseHas('game_roles', ['id' => $gameRole->id, 'name' => 'New Role']);
});

it('soft deletes a game role when actor has game-roles.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.delete');

    $gameRole = GameRole::factory()->create();

    $this->actingAs($actor)
        ->delete(route('game-roles.destroy', $gameRole))
        ->assertRedirect(route('game-roles.index'));

    $this->assertSoftDeleted('game_roles', ['id' => $gameRole->id]);
});

it('excludes soft-deleted game roles from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('game-roles.view');

    $gameRole = GameRole::factory()->create();
    $gameRole->delete();

    $response = $this->actingAs($viewer)->get(route('game-roles.index'));

    $gameRoles = $response->viewData('gameRoles');
    expect($gameRoles->contains('id', $gameRole->id))->toBeFalse();
});

it('restores a soft-deleted game role when actor has game-roles.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.restore');

    $gameRole = GameRole::factory()->create();
    $gameRole->delete();

    $this->actingAs($actor)
        ->post(route('game-roles.restore', $gameRole->id))
        ->assertRedirect(route('game-roles.index'));

    $this->assertNotSoftDeleted('game_roles', ['id' => $gameRole->id]);
});

it('records an activity log entry when a game role is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.create');

    $this->actingAs($actor)
        ->post(route('game-roles.store'), ['name' => 'Logged Role']);

    $gameRole = GameRole::where('name', 'Logged Role')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', GameRole::class)
            ->where('record_id', $gameRole->id)
            ->exists()
    )->toBeTrue();
});
