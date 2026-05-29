<?php

// tests/Feature/Scheduling/GameCrudTest.php

use App\Enums\GameType;
use App\Models\ActivityLog;
use App\Models\Competition;
use App\Models\Game;
use App\Models\User;

it('redirects unauthenticated user away from games index', function () {
    $this->get(route('games.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks games.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('games.index'))
        ->assertForbidden();
});

it('lists games when user has games.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('games.view');

    Game::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('games.index'))
        ->assertOk()
        ->assertViewIs('scheduling.index');
});

it('creates a game when actor has games.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.create');

    $competition = Competition::factory()->create();

    $this->actingAs($actor)
        ->post(route('games.store'), [
            'competition_id' => $competition->id,
            'game_type' => GameType::Match->value,
            'scheduled_at' => '2026-06-01 18:00:00',
            'field_number' => 1,
        ])
        ->assertRedirect(route('games.index'));

    $this->assertDatabaseHas('games', [
        'competition_id' => $competition->id,
        'field_number' => 1,
    ]);
});

it('fails validation when creating game with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.create');

    $this->actingAs($actor)
        ->post(route('games.store'), [])
        ->assertSessionHasErrors(['competition_id', 'game_type', 'scheduled_at']);
});

it('updates a game when actor has games.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.update');

    $game = Game::factory()->create(['field_number' => 1]);

    $this->actingAs($actor)
        ->put(route('games.update', $game), [
            'competition_id' => $game->competition_id,
            'game_type' => $game->game_type->value,
            'scheduled_at' => $game->scheduled_at->format('Y-m-d H:i:s'),
            'field_number' => 2,
        ])
        ->assertRedirect(route('games.index'));

    $this->assertDatabaseHas('games', ['id' => $game->id, 'field_number' => 2]);
});

it('soft deletes a game when actor has games.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.delete');

    $game = Game::factory()->create();

    $this->actingAs($actor)
        ->delete(route('games.destroy', $game))
        ->assertRedirect(route('games.index'));

    $this->assertSoftDeleted('games', ['id' => $game->id]);
});

it('excludes soft-deleted games from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('games.view');

    $game = Game::factory()->create();
    $game->delete();

    $response = $this->actingAs($viewer)->get(route('games.index'));

    $games = $response->viewData('games');
    expect($games->contains('id', $game->id))->toBeFalse();
});

it('restores a soft-deleted game when actor has games.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.restore');

    $game = Game::factory()->create();
    $game->delete();

    $this->actingAs($actor)
        ->post(route('games.restore', $game->id))
        ->assertRedirect(route('games.index'));

    $this->assertNotSoftDeleted('games', ['id' => $game->id]);
});

it('records an activity log entry when a game is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.create');

    $competition = Competition::factory()->create();

    $this->actingAs($actor)
        ->post(route('games.store'), [
            'competition_id' => $competition->id,
            'game_type' => GameType::Match->value,
            'scheduled_at' => '2026-07-01 20:00:00',
            'field_number' => 3,
        ]);

    $game = Game::where('competition_id', $competition->id)->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Game::class)
            ->where('record_id', $game->id)
            ->exists()
    )->toBeTrue();
});
