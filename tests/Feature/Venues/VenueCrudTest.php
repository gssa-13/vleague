<?php

// tests/Feature/Venues/VenueCrudTest.php

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Venue;

// ─── Authentication guard ────────────────────────────────────────────────────

it('redirects unauthenticated user away from venues index', function () {
    $this->get(route('venues.index'))
        ->assertRedirect(route('login'));
});

// ─── Authorization ───────────────────────────────────────────────────────────

it('returns 403 when user lacks venues.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('venues.index'))
        ->assertForbidden();
});

it('lists venues when user has venues.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('venues.view');

    Venue::factory()->count(3)->create();

    $this->actingAs($viewer)
        ->get(route('venues.index'))
        ->assertOk()
        ->assertViewIs('venues.index');
});

// ─── Create ──────────────────────────────────────────────────────────────────

it('creates a venue when actor has venues.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('venues.create');

    $this->actingAs($actor)
        ->post(route('venues.store'), [
            'name' => 'Lindavista',
            'address' => 'Av. Principal 123',
            'city' => 'CDMX',
            'max_fields' => 4,
            'match_duration_minutes' => 50,
            'advance_booking_days' => 7,
        ])
        ->assertRedirect(route('venues.index'));

    $this->assertDatabaseHas('venues', ['name' => 'Lindavista']);
});

it('fails validation when creating venue with missing name', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('venues.create');

    $this->actingAs($actor)
        ->post(route('venues.store'), [])
        ->assertSessionHasErrors('name');
});

// ─── Update ──────────────────────────────────────────────────────────────────

it('updates a venue when actor has venues.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('venues.update');

    $venue = Venue::factory()->create(['name' => 'Old Name']);

    $this->actingAs($actor)
        ->put(route('venues.update', $venue), [
            'name' => 'New Name',
            'address' => $venue->address,
            'city' => $venue->city,
            'max_fields' => $venue->max_fields,
            'match_duration_minutes' => $venue->match_duration_minutes,
            'advance_booking_days' => $venue->advance_booking_days,
        ])
        ->assertRedirect(route('venues.index'));

    $this->assertDatabaseHas('venues', ['id' => $venue->id, 'name' => 'New Name']);
});

// ─── Soft Delete ─────────────────────────────────────────────────────────────

it('soft deletes a venue when actor has venues.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('venues.delete');

    $venue = Venue::factory()->create();

    $this->actingAs($actor)
        ->delete(route('venues.destroy', $venue))
        ->assertRedirect(route('venues.index'));

    $this->assertSoftDeleted('venues', ['id' => $venue->id]);
});

it('excludes soft-deleted venues from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('venues.view');

    $venue = Venue::factory()->create();
    $venue->delete();

    $response = $this->actingAs($viewer)->get(route('venues.index'));

    $response->assertOk();
    $venues = $response->viewData('venues');
    expect($venues->contains('id', $venue->id))->toBeFalse();
});

// ─── Restore ─────────────────────────────────────────────────────────────────

it('restores a soft-deleted venue when actor has venues.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('venues.restore');

    $venue = Venue::factory()->create();
    $venue->delete();

    $this->actingAs($actor)
        ->post(route('venues.restore', $venue->id))
        ->assertRedirect(route('venues.index'));

    $this->assertNotSoftDeleted('venues', ['id' => $venue->id]);
});

// ─── Audit ───────────────────────────────────────────────────────────────────

it('records an activity log entry when a venue is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('venues.create');

    $this->actingAs($actor)
        ->post(route('venues.store'), [
            'name' => 'Audited Venue',
            'address' => 'Calle 1',
            'city' => 'CDMX',
            'max_fields' => 2,
            'match_duration_minutes' => 50,
            'advance_booking_days' => 5,
        ]);

    $venue = Venue::where('name', 'Audited Venue')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Venue::class)
            ->where('record_id', $venue->id)
            ->exists()
    )->toBeTrue();
});
