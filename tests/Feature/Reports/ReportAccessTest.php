<?php

// tests/Feature/Reports/ReportAccessTest.php

use App\Models\User;

it('redirects unauthenticated user away from reports', function () {
    $this->get(route('reports.financial.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks reports.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('reports.financial.index'))
        ->assertForbidden();
});

it('shows the financial report when user has reports.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('reports.view');

    $this->actingAs($viewer)
        ->get(route('reports.financial.index'))
        ->assertOk()
        ->assertViewIs('reports.financial');
});

it('returns 403 when exporting without reports.export permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('reports.view');

    $this->actingAs($viewer)
        ->get(route('reports.financial.export'))
        ->assertForbidden();
});

it('exports the financial report as CSV when user has reports.export permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo(['reports.view', 'reports.export']);

    $response = $this->actingAs($actor)->get(route('reports.financial.export'));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('text/csv');
});
