<?php

// tests/Feature/Payroll/PayrollCrudTest.php

use App\Enums\PayrollStatus;
use App\Models\ActivityLog;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Venue;

it('redirects unauthenticated user away from payrolls index', function () {
    $this->get(route('payrolls.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks payroll.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('payrolls.index'))
        ->assertForbidden();
});

it('lists payrolls when user has payroll.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('payroll.view');

    Payroll::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('payrolls.index'))
        ->assertOk()
        ->assertViewIs('payroll.index');
});

it('creates a payroll when actor has payroll.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payroll.create');

    $venue = Venue::factory()->create();

    $this->actingAs($actor)
        ->post(route('payrolls.store'), [
            'venue_id' => $venue->id,
            'period' => '2026-06',
        ])
        ->assertRedirect(route('payrolls.index'));

    $this->assertDatabaseHas('payrolls', [
        'venue_id' => $venue->id,
        'period' => '2026-06',
        'status' => PayrollStatus::Draft->value,
    ]);
});

it('fails validation when creating payroll with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payroll.create');

    $this->actingAs($actor)
        ->post(route('payrolls.store'), [])
        ->assertSessionHasErrors(['venue_id', 'period']);
});

it('soft deletes a payroll when actor has payroll.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payroll.delete');

    $payroll = Payroll::factory()->create();

    $this->actingAs($actor)
        ->delete(route('payrolls.destroy', $payroll))
        ->assertRedirect(route('payrolls.index'));

    $this->assertSoftDeleted('payrolls', ['id' => $payroll->id]);
});

it('excludes soft-deleted payrolls from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('payroll.view');

    $payroll = Payroll::factory()->create();
    $payroll->delete();

    $response = $this->actingAs($viewer)->get(route('payrolls.index'));

    $payrolls = $response->viewData('payrolls');
    expect($payrolls->contains('id', $payroll->id))->toBeFalse();
});

it('restores a soft-deleted payroll when actor has payroll.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payroll.restore');

    $payroll = Payroll::factory()->create();
    $payroll->delete();

    $this->actingAs($actor)
        ->post(route('payrolls.restore', $payroll->id))
        ->assertRedirect(route('payrolls.index'));

    $this->assertNotSoftDeleted('payrolls', ['id' => $payroll->id]);
});

it('cancels a payroll when actor has payroll.cancel permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payroll.cancel');

    $payroll = Payroll::factory()->create(['status' => PayrollStatus::Draft]);

    $this->actingAs($actor)
        ->post(route('payrolls.cancel', $payroll))
        ->assertRedirect(route('payrolls.index'));

    $payroll->refresh();

    expect($payroll->status)->toBe(PayrollStatus::Cancelled);
});

it('returns 403 when user without payroll.cancel tries to cancel', function () {
    $user = User::factory()->create();

    $payroll = Payroll::factory()->create();

    $this->actingAs($user)
        ->post(route('payrolls.cancel', $payroll))
        ->assertForbidden();
});

it('records an activity log entry when a payroll is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payroll.create');

    $venue = Venue::factory()->create();

    $this->actingAs($actor)
        ->post(route('payrolls.store'), [
            'venue_id' => $venue->id,
            'period' => '2026-07',
        ]);

    $payroll = Payroll::where('period', '2026-07')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Payroll::class)
            ->where('record_id', $payroll->id)
            ->exists()
    )->toBeTrue();
});
