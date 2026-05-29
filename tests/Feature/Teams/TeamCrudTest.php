<?php

// tests/Feature/Teams/TeamCrudTest.php

use App\Models\ActivityLog;
use App\Models\Team;
use App\Models\User;

it('redirects unauthenticated user away from teams index', function () {
    $this->get(route('teams.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks teams.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('teams.index'))
        ->assertForbidden();
});

it('lists teams when user has teams.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('teams.view');

    Team::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('teams.index'))
        ->assertOk()
        ->assertViewIs('teams.index');
});

it('creates a team when actor has teams.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.create');

    $this->actingAs($actor)
        ->post(route('teams.store'), ['name' => 'Real Madrid'])
        ->assertRedirect(route('teams.index'));

    $this->assertDatabaseHas('teams', ['name' => 'Real Madrid']);
});

it('fails validation when creating team with missing name', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.create');

    $this->actingAs($actor)
        ->post(route('teams.store'), [])
        ->assertSessionHasErrors('name');
});

it('updates a team when actor has teams.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.update');

    $team = Team::factory()->create(['name' => 'Old Name']);

    $this->actingAs($actor)
        ->put(route('teams.update', $team), ['name' => 'New Name'])
        ->assertRedirect(route('teams.index'));

    $this->assertDatabaseHas('teams', ['id' => $team->id, 'name' => 'New Name']);
});

it('soft deletes a team when actor has teams.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.delete');

    $team = Team::factory()->create();

    $this->actingAs($actor)
        ->delete(route('teams.destroy', $team))
        ->assertRedirect(route('teams.index'));

    $this->assertSoftDeleted('teams', ['id' => $team->id]);
});

it('excludes soft-deleted teams from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('teams.view');

    $team = Team::factory()->create();
    $team->delete();

    $response = $this->actingAs($viewer)->get(route('teams.index'));

    $teams = $response->viewData('teams');
    expect($teams->contains('id', $team->id))->toBeFalse();
});

it('restores a soft-deleted team when actor has teams.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.restore');

    $team = Team::factory()->create();
    $team->delete();

    $this->actingAs($actor)
        ->post(route('teams.restore', $team->id))
        ->assertRedirect(route('teams.index'));

    $this->assertNotSoftDeleted('teams', ['id' => $team->id]);
});

it('records an activity log entry when a team is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('teams.create');

    $this->actingAs($actor)
        ->post(route('teams.store'), ['name' => 'Logged Team']);

    $team = Team::where('name', 'Logged Team')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Team::class)
            ->where('record_id', $team->id)
            ->exists()
    )->toBeTrue();
});
