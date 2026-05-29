<?php

// tests/Feature/Teams/TeamRosterTest.php

use App\Models\CompetitionTeam;
use App\Models\Player;
use App\Models\TeamRoster;
use App\Models\User;

it('adds a player to a roster when actor has teams.assign permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.assign');

    $competitionTeam = CompetitionTeam::factory()->create();
    $player = Player::factory()->create();

    $this->actingAs($actor)
        ->post(route('competition-teams.roster.store', $competitionTeam), [
            'player_id' => $player->id,
            'jersey_number' => 10,
            'is_captain' => true,
            'is_wildcard' => false,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('team_rosters', [
        'competition_team_id' => $competitionTeam->id,
        'player_id' => $player->id,
        'jersey_number' => 10,
    ]);
});

it('soft deletes a roster entry when a player is removed', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.assign');

    $roster = TeamRoster::factory()->create();

    $this->actingAs($actor)
        ->delete(route('competition-teams.roster.destroy', [$roster->competition_team_id, $roster]))
        ->assertRedirect();

    $this->assertSoftDeleted('team_rosters', ['id' => $roster->id]);
});

it('returns 403 when user lacks teams.assign permission', function () {
    $user = User::factory()->create();

    $competitionTeam = CompetitionTeam::factory()->create();
    $player = Player::factory()->create();

    $this->actingAs($user)
        ->post(route('competition-teams.roster.store', $competitionTeam), [
            'player_id' => $player->id,
        ])
        ->assertForbidden();
});
