<?php

use App\Models\User;

beforeEach(function () {
    config()->set('locales.supported', [
        'en' => ['name' => 'English', 'label_key' => 'common.languages.english'],
        'es' => ['name' => 'Spanish', 'label_key' => 'common.languages.spanish'],
    ]);
});

it('stores the selected locale in session for guests', function () {
    $this->post(route('locale.update'), ['locale' => 'es'])
        ->assertRedirect();

    expect(session('locale'))->toBe('es');
});

it('stores the selected locale on the authenticated user', function () {
    $user = User::factory()->create(['locale' => null]);

    $this->actingAs($user)
        ->post(route('locale.update'), ['locale' => 'es'])
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'locale' => 'es',
    ]);
    expect(session('locale'))->toBe('es');
});

it('rejects unsupported locales', function () {
    $this->from(route('dashboard'))
        ->post(route('locale.update'), ['locale' => 'fr'])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHasErrors('locale');
});

it('shows the selected language beside the profile menu', function () {
    $user = User::factory()->create(['locale' => 'es']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('id="desktop-language-menu"', false)
        ->assertSee('id="desktop-profile-menu"', false)
        ->assertSeeInOrder([
            'id="desktop-language-menu"',
            'id="desktop-profile-menu"',
        ], false)
        ->assertSee('data-current-locale="es"', false)
        ->assertSee('aria-current="true"', false)
        ->assertSee('Español');
});
