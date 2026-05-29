<?php

// tests/Feature/Prices/PriceCrudTest.php

use App\Enums\PriceStatus;
use App\Models\ActivityLog;
use App\Models\Price;
use App\Models\User;

it('redirects unauthenticated user away from prices index', function () {
    $this->get(route('prices.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks prices.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('prices.index'))
        ->assertForbidden();
});

it('lists prices when user has prices.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('prices.view');

    Price::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('prices.index'))
        ->assertOk()
        ->assertViewIs('prices.index');
});

it('creates a price when actor has prices.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('prices.create');

    $this->actingAs($actor)
        ->post(route('prices.store'), [
            'name' => 'Standard',
            'amount' => '1500.00',
            'currency' => 'MXN',
            'status' => PriceStatus::Active->value,
        ])
        ->assertRedirect(route('prices.index'));

    $this->assertDatabaseHas('prices', ['name' => 'Standard']);
});

it('fails validation when creating price with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('prices.create');

    $this->actingAs($actor)
        ->post(route('prices.store'), [])
        ->assertSessionHasErrors(['name', 'amount', 'currency', 'status']);
});

it('updates a price when actor has prices.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('prices.update');

    $price = Price::factory()->create(['name' => 'Old Price']);

    $this->actingAs($actor)
        ->put(route('prices.update', $price), [
            'name' => 'New Price',
            'amount' => $price->amount,
            'currency' => $price->currency,
            'status' => $price->status->value,
        ])
        ->assertRedirect(route('prices.index'));

    $this->assertDatabaseHas('prices', ['id' => $price->id, 'name' => 'New Price']);
});

it('soft deletes a price when actor has prices.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('prices.delete');

    $price = Price::factory()->create();

    $this->actingAs($actor)
        ->delete(route('prices.destroy', $price))
        ->assertRedirect(route('prices.index'));

    $this->assertSoftDeleted('prices', ['id' => $price->id]);
});

it('excludes soft-deleted prices from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('prices.view');

    $price = Price::factory()->create();
    $price->delete();

    $response = $this->actingAs($viewer)->get(route('prices.index'));

    $prices = $response->viewData('prices');
    expect($prices->contains('id', $price->id))->toBeFalse();
});

it('restores a soft-deleted price when actor has prices.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('prices.restore');

    $price = Price::factory()->create();
    $price->delete();

    $this->actingAs($actor)
        ->post(route('prices.restore', $price->id))
        ->assertRedirect(route('prices.index'));

    $this->assertNotSoftDeleted('prices', ['id' => $price->id]);
});

it('records an activity log entry when a price is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('prices.create');

    $this->actingAs($actor)
        ->post(route('prices.store'), [
            'name' => 'Logged Price',
            'amount' => '500.00',
            'currency' => 'MXN',
            'status' => PriceStatus::Active->value,
        ]);

    $price = Price::where('name', 'Logged Price')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Price::class)
            ->where('record_id', $price->id)
            ->exists()
    )->toBeTrue();
});
