<?php

// tests/Feature/Competitions/CompetitionCrudTest.php

use App\Models\ActivityLog;
use App\Models\Competition;
use App\Models\Division;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from competitions index', function () {
    $this->get(route('competitions.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks competitions.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('competitions.index'))
        ->assertForbidden();
});

it('lists competitions when user has competitions.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('competitions.view');

    Competition::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('competitions.index'))
        ->assertOk()
        ->assertViewIs('competitions.index');
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a competition when actor has competitions.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.create');

    $venue = Venue::factory()->create();
    $tournament = Tournament::factory()->create(['venue_id' => $venue->id]);
    $division = Division::factory()->create(['tournament_id' => $tournament->id]);

    $this->actingAs($actor)
        ->post(route('competitions.store'), [
            'venue_id' => $venue->id,
            'tournament_id' => $tournament->id,
            'division_id' => $division->id,
        ])
        ->assertRedirect(route('competitions.index'));

    $this->assertDatabaseHas('competitions', [
        'venue_id' => $venue->id,
        'tournament_id' => $tournament->id,
        'division_id' => $division->id,
    ]);
});

it('fails validation when creating competition with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.create');

    $this->actingAs($actor)
        ->post(route('competitions.store'), [])
        ->assertSessionHasErrors(['venue_id', 'tournament_id', 'division_id']);
});

it('enforces unique combination of venue/tournament/division among non-deleted', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.create');

    $competition = Competition::factory()->create();

    $this->actingAs($actor)
        ->post(route('competitions.store'), [
            'venue_id' => $competition->venue_id,
            'tournament_id' => $competition->tournament_id,
            'division_id' => $competition->division_id,
        ])
        ->assertSessionHasErrors();
});

it('allows reusing combination after soft delete', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.create');

    $competition = Competition::factory()->create();
    $competition->delete();

    $this->actingAs($actor)
        ->post(route('competitions.store'), [
            'venue_id' => $competition->venue_id,
            'tournament_id' => $competition->tournament_id,
            'division_id' => $competition->division_id,
        ])
        ->assertRedirect(route('competitions.index'));
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a competition when actor has competitions.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.delete');

    $competition = Competition::factory()->create();

    $this->actingAs($actor)
        ->delete(route('competitions.destroy', $competition))
        ->assertRedirect(route('competitions.index'));

    $this->assertSoftDeleted('competitions', ['id' => $competition->id]);
});

it('excludes soft-deleted competitions from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('competitions.view');

    $competition = Competition::factory()->create();
    $competition->delete();

    $response = $this->actingAs($viewer)->get(route('competitions.index'));

    $response->assertOk();
    $competitions = $response->viewData('competitions');
    expect($competitions->contains('id', $competition->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted competition when actor has competitions.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.restore');

    $competition = Competition::factory()->create();
    $competition->delete();

    $this->actingAs($actor)
        ->post(route('competitions.restore', $competition->id))
        ->assertRedirect(route('competitions.index'));

    $this->assertNotSoftDeleted('competitions', ['id' => $competition->id]);
});

// ─── Audit ───────────────────────────────────────────────────────────────────

it('records an activity log entry when a competition is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('competitions.create');

    $venue = Venue::factory()->create();
    $tournament = Tournament::factory()->create(['venue_id' => $venue->id]);
    $division = Division::factory()->create(['tournament_id' => $tournament->id]);

    $this->actingAs($actor)
        ->post(route('competitions.store'), [
            'venue_id' => $venue->id,
            'tournament_id' => $tournament->id,
            'division_id' => $division->id,
        ]);

    $competition = Competition::where('division_id', $division->id)->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Competition::class)
            ->where('record_id', $competition->id)
            ->exists()
    )->toBeTrue();
});
