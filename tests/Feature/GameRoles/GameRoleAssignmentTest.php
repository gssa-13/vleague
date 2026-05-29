<?php

// tests/Feature/GameRoles/GameRoleAssignmentTest.php

use App\Models\Employee;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\GameRoleAssignment;
use App\Models\User;

it('assigns a role to a game when actor has game-roles.assign permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.assign');

    $game = Game::factory()->create();
    $gameRole = GameRole::factory()->create();
    $employee = Employee::factory()->create();

    $this->actingAs($actor)
        ->post(route('games.roles.assign', $game), [
            'game_role_id' => $gameRole->id,
            'employee_id' => $employee->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('game_role_assignments', [
        'game_id' => $game->id,
        'game_role_id' => $gameRole->id,
        'employee_id' => $employee->id,
    ]);
});

it('soft deletes an assignment when it is revoked', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('game-roles.assign');

    $assignment = GameRoleAssignment::factory()->create();

    $this->actingAs($actor)
        ->delete(route('games.roles.revoke', [$assignment->game_id, $assignment]))
        ->assertRedirect();

    $this->assertSoftDeleted('game_role_assignments', ['id' => $assignment->id]);
});

it('returns 403 when user lacks game-roles.assign permission', function () {
    $user = User::factory()->create();

    $game = Game::factory()->create();
    $gameRole = GameRole::factory()->create();

    $this->actingAs($user)
        ->post(route('games.roles.assign', $game), [
            'game_role_id' => $gameRole->id,
        ])
        ->assertForbidden();
});
