<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::middleware('web')->get('/locale-probe', fn () => response()->json([
        'locale' => app()->getLocale(),
    ]))->name('locale.probe');

    config()->set('app.locale', 'en');
    config()->set('app.fallback_locale', 'en');
    config()->set('locales.supported', [
        'en' => ['name' => 'English', 'label_key' => 'common.languages.english'],
        'es' => ['name' => 'Spanish', 'label_key' => 'common.languages.spanish'],
    ]);
});

it('uses the default locale when no preference exists', function () {
    $this->get('/locale-probe')
        ->assertOk()
        ->assertJson(['locale' => 'en']);
});

it('uses the locale stored in session', function () {
    $this->withSession(['locale' => 'es'])
        ->get('/locale-probe')
        ->assertOk()
        ->assertJson(['locale' => 'es']);
});

it('prefers the authenticated user locale over the session locale', function () {
    $user = User::factory()->create(['locale' => 'es']);

    $this->withSession(['locale' => 'en'])
        ->actingAs($user)
        ->get('/locale-probe')
        ->assertOk()
        ->assertJson(['locale' => 'es']);
});
