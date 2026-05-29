<?php

// tests/Feature/Tournaments/TournamentCrudTest.php

use App\Enums\TournamentStatus;
use App\Models\ActivityLog;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from tournaments index', function () {
    $this->get(route('tournaments.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks tournaments.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('tournaments.index'))
        ->assertForbidden();
});

it('lists tournaments when user has tournaments.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('tournaments.view');

    Tournament::factory()->count(3)->create();

    $this->actingAs($viewer)
        ->get(route('tournaments.index'))
        ->assertOk()
        ->assertViewIs('tournaments.index');
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a tournament when actor has tournaments.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('tournaments.create');

    $venue = Venue::factory()->create();

    $this->actingAs($actor)
        ->post(route('tournaments.store'), [
            'venue_id' => $venue->id,
            'name' => 'Copa 2026',
            'starts_at' => '2026-01-01',
            'ends_at' => '2026-12-31',
            'status' => TournamentStatus::Active->value,
        ])
        ->assertRedirect(route('tournaments.index'));

    $this->assertDatabaseHas('tournaments', ['name' => 'Copa 2026']);
});

it('fails validation when creating tournament with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('tournaments.create');

    $this->actingAs($actor)
        ->post(route('tournaments.store'), [])
        ->assertSessionHasErrors(['venue_id', 'name', 'starts_at', 'ends_at', 'status']);
});

// ─── Update ──────────────────────────────────────────────────────────────────

it('updates a tournament when actor has tournaments.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('tournaments.update');

    $tournament = Tournament::factory()->create(['name' => 'Old Name']);

    $this->actingAs($actor)
        ->put(route('tournaments.update', $tournament), [
            'venue_id' => $tournament->venue_id,
            'name' => 'New Name',
            'starts_at' => $tournament->starts_at->toDateString(),
            'ends_at' => $tournament->ends_at->toDateString(),
            'status' => $tournament->status->value,
        ])
        ->assertRedirect(route('tournaments.index'));

    $this->assertDatabaseHas('tournaments', ['id' => $tournament->id, 'name' => 'New Name']);
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a tournament when actor has tournaments.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('tournaments.delete');

    $tournament = Tournament::factory()->create();

    $this->actingAs($actor)
        ->delete(route('tournaments.destroy', $tournament))
        ->assertRedirect(route('tournaments.index'));

    $this->assertSoftDeleted('tournaments', ['id' => $tournament->id]);
});

it('excludes soft-deleted tournaments from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('tournaments.view');

    $tournament = Tournament::factory()->create();
    $tournament->delete();

    $response = $this->actingAs($viewer)->get(route('tournaments.index'));

    $response->assertOk();
    $tournaments = $response->viewData('tournaments');
    expect($tournaments->contains('id', $tournament->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted tournament when actor has tournaments.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('tournaments.restore');

    $tournament = Tournament::factory()->create();
    $tournament->delete();

    $this->actingAs($actor)
        ->post(route('tournaments.restore', $tournament->id))
        ->assertRedirect(route('tournaments.index'));

    $this->assertNotSoftDeleted('tournaments', ['id' => $tournament->id]);
});

// ─── Audit ───────────────────────────────────────────────────────────────────

it('records an activity log entry when a tournament is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('tournaments.create');

    $venue = Venue::factory()->create();

    $this->actingAs($actor)
        ->post(route('tournaments.store'), [
            'venue_id' => $venue->id,
            'name' => 'Logged Tournament',
            'starts_at' => '2026-01-01',
            'ends_at' => '2026-12-31',
            'status' => TournamentStatus::Active->value,
        ]);

    $tournament = Tournament::where('name', 'Logged Tournament')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Tournament::class)
            ->where('record_id', $tournament->id)
            ->exists()
    )->toBeTrue();
});
