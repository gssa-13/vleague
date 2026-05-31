<?php

// tests/Feature/Audit/AuditExportTest.php

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Venue;

it('returns 403 when exporting without audit.export permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('audit.view');

    $this->actingAs($viewer)
        ->get(route('audit.export'))
        ->assertForbidden();
});

it('exports audit logs as CSV when user has audit.export permission', function () {
    $actor = User::factory()->create(['name' => 'CSV Actor']);
    $exporter = User::factory()->create();
    $exporter->givePermissionTo('audit.export');

    ActivityLog::factory()->updated()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $actor->id,
        'record_id' => 15,
        'column_name' => 'name',
        'old_value' => 'CSV Before',
        'new_value' => 'CSV After',
    ]);

    $response = $this->actingAs($exporter)
        ->get(route('audit.export', ['format' => 'csv']));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('text/csv');
    $response->assertSee('CSV Actor', false)
        ->assertSee('venues', false)
        ->assertSee('CSV Before', false)
        ->assertSee('CSV After', false);
});

it('exports audit logs as JSON when user has audit.export permission', function () {
    $actor = User::factory()->create(['name' => 'JSON Actor']);
    $exporter = User::factory()->create();
    $exporter->givePermissionTo('audit.export');

    ActivityLog::factory()->updated()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $actor->id,
        'record_id' => 22,
        'column_name' => 'name',
        'old_value' => 'JSON Before',
        'new_value' => 'JSON After',
    ]);

    $this->actingAs($exporter)
        ->getJson(route('audit.export', ['format' => 'json']))
        ->assertOk()
        ->assertJsonPath('data.0.table_name', 'venues')
        ->assertJsonPath('data.0.user.name', 'JSON Actor')
        ->assertJsonPath('data.0.old_value', 'JSON Before')
        ->assertJsonPath('data.0.new_value', 'JSON After');
});
