<?php

// tests/Feature/Finance/PaymentCancellationTest.php

use App\Enums\PaymentStatus;
use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\User;

it('cancels a payment with a reason when actor has payments.cancel permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.cancel');

    $payment = Payment::factory()->create(['status' => PaymentStatus::Paid]);

    $this->actingAs($actor)
        ->post(route('payments.cancel', $payment), [
            'reason' => 'Charged by mistake',
        ])
        ->assertRedirect(route('payments.index'));

    $payment->refresh();

    expect($payment->status)->toBe(PaymentStatus::Cancelled)
        ->and($payment->trashed())->toBeTrue();

    $this->assertDatabaseHas('payment_cancellations', [
        'payment_id' => $payment->id,
        'reason' => 'Charged by mistake',
    ]);
});

it('returns 403 when user lacks payments.cancel permission', function () {
    $user = User::factory()->create();

    $payment = Payment::factory()->create();

    $this->actingAs($user)
        ->post(route('payments.cancel', $payment), ['reason' => 'x'])
        ->assertForbidden();
});

it('records an audit entry when a payment is cancelled', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('payments.cancel');

    $payment = Payment::factory()->create(['status' => PaymentStatus::Paid]);

    $this->actingAs($actor)
        ->post(route('payments.cancel', $payment), ['reason' => 'Audited cancel']);

    expect(
        ActivityLog::where('model', Payment::class)
            ->where('record_id', $payment->id)
            ->where('action', 'deleted')
            ->exists()
    )->toBeTrue();
});

it('excludes cancelled payments from the active listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo(['payments.view', 'payments.cancel']);

    $payment = Payment::factory()->create(['status' => PaymentStatus::Paid]);

    $this->actingAs($viewer)
        ->post(route('payments.cancel', $payment), ['reason' => 'Removed']);

    $response = $this->actingAs($viewer)->get(route('payments.index'));

    $payments = $response->viewData('payments');
    expect($payments->contains('id', $payment->id))->toBeFalse();
});
