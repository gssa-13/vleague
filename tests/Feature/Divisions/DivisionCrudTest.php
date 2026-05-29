<?php

// tests/Feature/Divisions/DivisionCrudTest.php

use App\Enums\DivisionDay;
use App\Models\ActivityLog;
use App\Models\Division;
use App\Models\Tournament;
use App\Models\User;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from divisions index', function () {
    $this->get(route('divisions.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks divisions.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('divisions.index'))
        ->assertForbidden();
});

it('lists divisions when user has divisions.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('divisions.view');

    Division::factory()->count(3)->create();

    $this->actingAs($viewer)
        ->get(route('divisions.index'))
        ->assertOk()
        ->assertViewIs('divisions.index');
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a division when actor has divisions.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('divisions.create');

    $tournament = Tournament::factory()->create();

    $this->actingAs($actor)
        ->post(route('divisions.store'), [
            'tournament_id' => $tournament->id,
            'day' => DivisionDay::Saturday->value,
            'field_number' => 1,
            'group_letter' => 'A',
        ])
        ->assertRedirect(route('divisions.index'));

    $this->assertDatabaseHas('divisions', [
        'tournament_id' => $tournament->id,
        'day' => DivisionDay::Saturday->value,
    ]);
});

it('fails validation when creating division with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('divisions.create');

    $this->actingAs($actor)
        ->post(route('divisions.store'), [])
        ->assertSessionHasErrors(['tournament_id', 'day', 'field_number']);
});

// ─── Update ──────────────────────────────────────────────────────────────────

it('updates a division when actor has divisions.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('divisions.update');

    $division = Division::factory()->create(['field_number' => 1]);

    $this->actingAs($actor)
        ->put(route('divisions.update', $division), [
            'tournament_id' => $division->tournament_id,
            'day' => $division->day->value,
            'field_number' => 2,
            'group_letter' => $division->group_letter,
        ])
        ->assertRedirect(route('divisions.index'));

    $this->assertDatabaseHas('divisions', ['id' => $division->id, 'field_number' => 2]);
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a division when actor has divisions.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('divisions.delete');

    $division = Division::factory()->create();

    $this->actingAs($actor)
        ->delete(route('divisions.destroy', $division))
        ->assertRedirect(route('divisions.index'));

    $this->assertSoftDeleted('divisions', ['id' => $division->id]);
});

it('excludes soft-deleted divisions from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('divisions.view');

    $division = Division::factory()->create();
    $division->delete();

    $response = $this->actingAs($viewer)->get(route('divisions.index'));

    $response->assertOk();
    $divisions = $response->viewData('divisions');
    expect($divisions->contains('id', $division->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted division when actor has divisions.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('divisions.restore');

    $division = Division::factory()->create();
    $division->delete();

    $this->actingAs($actor)
        ->post(route('divisions.restore', $division->id))
        ->assertRedirect(route('divisions.index'));

    $this->assertNotSoftDeleted('divisions', ['id' => $division->id]);
});

// ─── Audit ───────────────────────────────────────────────────────────────────

it('records an activity log entry when a division is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('divisions.create');

    $tournament = Tournament::factory()->create();

    $this->actingAs($actor)
        ->post(route('divisions.store'), [
            'tournament_id' => $tournament->id,
            'day' => DivisionDay::Monday->value,
            'field_number' => 3,
            'group_letter' => null,
        ]);

    $division = Division::where('tournament_id', $tournament->id)->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Division::class)
            ->where('record_id', $division->id)
            ->exists()
    )->toBeTrue();
});
