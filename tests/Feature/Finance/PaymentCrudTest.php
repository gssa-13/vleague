<?php

// tests/Feature/Finance/PaymentCrudTest.php

use App\Enums\PaymentConcept;
use App\Enums\PaymentMethod;
use App\Models\ActivityLog;
use App\Models\Competition;
use App\Models\Payment;
use App\Models\User;

it('redirects unauthenticated user away from payments index', function () {
    $this->get(route('payments.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks payments.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('payments.index'))
        ->assertForbidden();
});

it('lists payments when user has payments.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('payments.view');

    Payment::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('payments.index'))
        ->assertOk()
        ->assertViewIs('finance.payments.index');
});

it('creates a payment when actor has payments.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.create');

    $competition = Competition::factory()->create();

    $this->actingAs($actor)
        ->post(route('payments.store'), [
            'competition_id' => $competition->id,
            'concept' => PaymentConcept::Inscription->value,
            'payment_method' => PaymentMethod::Cash->value,
            'amount' => 1000.00,
            'paid_amount' => 600.00,
        ])
        ->assertRedirect(route('payments.index'));

    $this->assertDatabaseHas('payments', [
        'competition_id' => $competition->id,
        'amount' => 1000.00,
    ]);
});

it('fails validation when creating payment with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.create');

    $this->actingAs($actor)
        ->post(route('payments.store'), [])
        ->assertSessionHasErrors(['concept', 'payment_method', 'amount']);
});

it('updates a payment when actor has payments.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.update');

    $payment = Payment::factory()->create(['concept' => PaymentConcept::Inscription]);

    $this->actingAs($actor)
        ->put(route('payments.update', $payment), [
            'competition_id' => $payment->competition_id,
            'concept' => PaymentConcept::Deposit->value,
            'payment_method' => $payment->payment_method->value,
            'amount' => $payment->amount,
            'paid_amount' => $payment->paid_amount,
        ])
        ->assertRedirect(route('payments.index'));

    $this->assertDatabaseHas('payments', ['id' => $payment->id, 'concept' => PaymentConcept::Deposit->value]);
});

it('soft deletes a payment when actor has payments.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.delete');

    $payment = Payment::factory()->create();

    $this->actingAs($actor)
        ->delete(route('payments.destroy', $payment))
        ->assertRedirect(route('payments.index'));

    $this->assertSoftDeleted('payments', ['id' => $payment->id]);
});

it('excludes soft-deleted payments from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('payments.view');

    $payment = Payment::factory()->create();
    $payment->delete();

    $response = $this->actingAs($viewer)->get(route('payments.index'));

    $payments = $response->viewData('payments');
    expect($payments->contains('id', $payment->id))->toBeFalse();
});

it('restores a soft-deleted payment when actor has payments.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.restore');

    $payment = Payment::factory()->create();
    $payment->delete();

    $this->actingAs($actor)
        ->post(route('payments.restore', $payment->id))
        ->assertRedirect(route('payments.index'));

    $this->assertNotSoftDeleted('payments', ['id' => $payment->id]);
});

it('records an activity log entry when a payment is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.create');

    $competition = Competition::factory()->create();

    $this->actingAs($actor)
        ->post(route('payments.store'), [
            'competition_id' => $competition->id,
            'concept' => PaymentConcept::Inscription->value,
            'payment_method' => PaymentMethod::Cash->value,
            'amount' => 500.00,
            'paid_amount' => 500.00,
        ]);

    $payment = Payment::where('competition_id', $competition->id)->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Payment::class)
            ->where('record_id', $payment->id)
            ->exists()
    )->toBeTrue();
});
