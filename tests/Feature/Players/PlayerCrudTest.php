<?php

// tests/Feature/Players/PlayerCrudTest.php

use App\Models\ActivityLog;
use App\Models\Player;
use App\Models\User;

it('redirects unauthenticated user away from players index', function () {
    $this->get(route('players.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks players.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('players.index'))
        ->assertForbidden();
});

it('lists players when user has players.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('players.view');

    Player::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('players.index'))
        ->assertOk()
        ->assertViewIs('players.index');
});

it('creates a player when actor has players.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.create');

    $this->actingAs($actor)
        ->post(route('players.store'), [
            'first_name' => 'Carlos',
            'last_name' => 'López',
            'birth_date' => '1990-05-15',
        ])
        ->assertRedirect(route('players.index'));

    $this->assertDatabaseHas('players', ['first_name' => 'Carlos', 'last_name' => 'López']);
});

it('fails validation when creating player with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.create');

    $this->actingAs($actor)
        ->post(route('players.store'), [])
        ->assertSessionHasErrors(['first_name', 'last_name', 'birth_date']);
});

it('fails validation when creating player with duplicate email', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.create');

    Player::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($actor)
        ->post(route('players.store'), [
            'first_name' => 'Dup',
            'last_name' => 'Player',
            'birth_date' => '1990-01-01',
            'email' => 'taken@example.com',
        ])
        ->assertSessionHasErrors('email');
});

it('allows reusing email after soft delete', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.create');

    $deleted = Player::factory()->create(['email' => 'reuse@example.com']);
    $deleted->delete();

    $this->actingAs($actor)
        ->post(route('players.store'), [
            'first_name' => 'New',
            'last_name' => 'Player',
            'birth_date' => '1995-01-01',
            'email' => 'reuse@example.com',
        ])
        ->assertRedirect(route('players.index'));
});

it('updates a player when actor has players.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.update');

    $player = Player::factory()->create(['first_name' => 'Old']);

    $this->actingAs($actor)
        ->put(route('players.update', $player), [
            'first_name' => 'New',
            'last_name' => $player->last_name,
            'birth_date' => $player->birth_date->toDateString(),
        ])
        ->assertRedirect(route('players.index'));

    $this->assertDatabaseHas('players', ['id' => $player->id, 'first_name' => 'New']);
});

it('soft deletes a player when actor has players.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.delete');

    $player = Player::factory()->create();

    $this->actingAs($actor)
        ->delete(route('players.destroy', $player))
        ->assertRedirect(route('players.index'));

    $this->assertSoftDeleted('players', ['id' => $player->id]);
});

it('excludes soft-deleted players from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('players.view');

    $player = Player::factory()->create();
    $player->delete();

    $response = $this->actingAs($viewer)->get(route('players.index'));

    $players = $response->viewData('players');
    expect($players->contains('id', $player->id))->toBeFalse();
});

it('restores a soft-deleted player when actor has players.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.restore');

    $player = Player::factory()->create();
    $player->delete();

    $this->actingAs($actor)
        ->post(route('players.restore', $player->id))
        ->assertRedirect(route('players.index'));

    $this->assertNotSoftDeleted('players', ['id' => $player->id]);
});

it('records an activity log entry when a player is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('players.create');

    $this->actingAs($actor)
        ->post(route('players.store'), [
            'first_name' => 'Logged',
            'last_name' => 'Player',
            'birth_date' => '1992-03-10',
        ]);

    $player = Player::where('first_name', 'Logged')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Player::class)
            ->where('record_id', $player->id)
            ->exists()
    )->toBeTrue();
});
