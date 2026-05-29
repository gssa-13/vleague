<?php

// tests/Feature/PlayerSanctions/PlayerSanctionCrudTest.php

use App\Models\ActivityLog;
use App\Models\Player;
use App\Models\PlayerSanction;
use App\Models\User;

it('redirects unauthenticated user away from player sanctions index', function () {
    $this->get(route('player-sanctions.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks player-sanctions.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('player-sanctions.index'))
        ->assertForbidden();
});

it('lists player sanctions when user has player-sanctions.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('player-sanctions.view');

    PlayerSanction::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('player-sanctions.index'))
        ->assertOk()
        ->assertViewIs('player-sanctions.index');
});

it('creates a player sanction when actor has player-sanctions.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('player-sanctions.create');

    $player = Player::factory()->create();

    $this->actingAs($actor)
        ->post(route('player-sanctions.store'), [
            'player_id' => $player->id,
            'reason' => 'Red card',
            'sanctioned_games' => 3,
            'served_games' => 0,
        ])
        ->assertRedirect(route('player-sanctions.index'));

    $this->assertDatabaseHas('player_sanctions', [
        'player_id' => $player->id,
        'reason' => 'Red card',
    ]);
});

it('fails validation when creating sanction with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('player-sanctions.create');

    $this->actingAs($actor)
        ->post(route('player-sanctions.store'), [])
        ->assertSessionHasErrors(['player_id', 'reason', 'sanctioned_games']);
});

it('updates a player sanction when actor has player-sanctions.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('player-sanctions.update');

    $sanction = PlayerSanction::factory()->create(['served_games' => 0]);

    $this->actingAs($actor)
        ->put(route('player-sanctions.update', $sanction), [
            'player_id' => $sanction->player_id,
            'reason' => $sanction->reason,
            'sanctioned_games' => $sanction->sanctioned_games,
            'served_games' => 1,
        ])
        ->assertRedirect(route('player-sanctions.index'));

    $this->assertDatabaseHas('player_sanctions', ['id' => $sanction->id, 'served_games' => 1]);
});

it('soft deletes a player sanction when actor has player-sanctions.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('player-sanctions.delete');

    $sanction = PlayerSanction::factory()->create();

    $this->actingAs($actor)
        ->delete(route('player-sanctions.destroy', $sanction))
        ->assertRedirect(route('player-sanctions.index'));

    $this->assertSoftDeleted('player_sanctions', ['id' => $sanction->id]);
});

it('excludes soft-deleted sanctions from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('player-sanctions.view');

    $sanction = PlayerSanction::factory()->create();
    $sanction->delete();

    $response = $this->actingAs($viewer)->get(route('player-sanctions.index'));

    $sanctions = $response->viewData('sanctions');
    expect($sanctions->contains('id', $sanction->id))->toBeFalse();
});

it('restores a soft-deleted sanction when actor has player-sanctions.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('player-sanctions.restore');

    $sanction = PlayerSanction::factory()->create();
    $sanction->delete();

    $this->actingAs($actor)
        ->post(route('player-sanctions.restore', $sanction->id))
        ->assertRedirect(route('player-sanctions.index'));

    $this->assertNotSoftDeleted('player_sanctions', ['id' => $sanction->id]);
});

it('records an activity log entry when a sanction is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('player-sanctions.create');

    $player = Player::factory()->create();

    $this->actingAs($actor)
        ->post(route('player-sanctions.store'), [
            'player_id' => $player->id,
            'reason' => 'Logged sanction',
            'sanctioned_games' => 2,
            'served_games' => 0,
        ]);

    $sanction = PlayerSanction::where('reason', 'Logged sanction')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', PlayerSanction::class)
            ->where('record_id', $sanction->id)
            ->exists()
    )->toBeTrue();
});
