<?php

// tests/Feature/Scheduling/GameCancellationTest.php

use App\Enums\GameStatus;
use App\Models\CancellationReason;
use App\Models\Game;
use App\Models\User;

it('cancels a game with a reason when actor has games.cancel permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('games.cancel');

    $game = Game::factory()->create(['status' => GameStatus::Scheduled]);
    $reason = CancellationReason::factory()->create();

    $this->actingAs($actor)
        ->post(route('games.cancel', $game), [
            'cancellation_reason_id' => $reason->id,
        ])
        ->assertRedirect(route('games.index'));

    $game->refresh();

    expect($game->status)->toBe(GameStatus::Cancelled)
        ->and($game->cancellation_reason_id)->toBe($reason->id)
        ->and($game->trashed())->toBeTrue();
});

it('returns 403 when user lacks games.cancel permission', function () {
    $user = User::factory()->create();

    $game = Game::factory()->create();

    $this->actingAs($user)
        ->post(route('games.cancel', $game), [])
        ->assertForbidden();
});

it('excludes cancelled games from the active listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo(['games.view', 'games.cancel']);

    $game = Game::factory()->create(['status' => GameStatus::Scheduled]);
    $reason = CancellationReason::factory()->create();

    $this->actingAs($viewer)
        ->post(route('games.cancel', $game), ['cancellation_reason_id' => $reason->id]);

    $response = $this->actingAs($viewer)->get(route('games.index'));

    $games = $response->viewData('games');
    expect($games->contains('id', $game->id))->toBeFalse();
});
