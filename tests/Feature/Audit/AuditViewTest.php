<?php

// tests/Feature/Audit/AuditViewTest.php

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;

it('returns 403 when user lacks audit.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('audit.index'))
        ->assertForbidden();
});

it('lists audit logs when user has audit.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('audit.view');

    ActivityLog::factory()->updated()->create([
        'action' => 'updated',
        'model' => Venue::class,
        'table_name' => 'venues',
        'record_id' => 12,
        'column_name' => 'name',
        'old_value' => 'Old Venue',
        'new_value' => 'New Venue',
    ]);

    $this->actingAs($viewer)
        ->get(route('audit.index'))
        ->assertOk()
        ->assertViewIs('audit.index')
        ->assertSee('venues')
        ->assertSee('updated')
        ->assertSee('Old Venue')
        ->assertSee('New Venue');
});

it('does not list soft-deleted audit logs in the normal view', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('audit.view');

    ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'new_value' => 'Visible audit value',
    ]);

    $hiddenLog = ActivityLog::factory()->create([
        'model' => Role::class,
        'table_name' => 'roles',
        'new_value' => 'Hidden audit value',
    ]);
    $hiddenLog->delete();

    $this->actingAs($viewer)
        ->get(route('audit.index'))
        ->assertOk()
        ->assertSee('Visible audit value')
        ->assertDontSee('Hidden audit value');
});

it('filters audit logs by model user and date range', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('audit.view');

    $actor = User::factory()->create(['name' => 'Audit Actor']);
    $otherActor = User::factory()->create();

    ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $actor->id,
        'new_value' => 'Filtered audit value',
        'created_at' => '2026-05-15 10:00:00',
    ]);

    ActivityLog::factory()->create([
        'model' => Role::class,
        'table_name' => 'roles',
        'user_id' => $actor->id,
        'new_value' => 'Wrong model value',
        'created_at' => '2026-05-15 10:00:00',
    ]);

    ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $otherActor->id,
        'new_value' => 'Wrong user value',
        'created_at' => '2026-05-15 10:00:00',
    ]);

    ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $actor->id,
        'new_value' => 'Wrong date value',
        'created_at' => '2026-06-15 10:00:00',
    ]);

    $this->actingAs($viewer)
        ->get(route('audit.index', [
            'model' => Venue::class,
            'user_id' => $actor->id,
            'from' => '2026-05-01',
            'to' => '2026-05-31',
        ]))
        ->assertOk()
        ->assertSee('Filtered audit value')
        ->assertSee('Audit Actor')
        ->assertDontSee('Wrong model value')
        ->assertDontSee('Wrong user value')
        ->assertDontSee('Wrong date value');
});

it('shows audit log details when user has audit.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('audit.view');

    $log = ActivityLog::factory()->updated()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'record_id' => 77,
        'column_name' => 'name',
        'old_value' => 'Before Detail',
        'new_value' => 'After Detail',
    ]);

    $this->actingAs($viewer)
        ->get(route('audit.show', $log))
        ->assertOk()
        ->assertViewIs('audit.show')
        ->assertSee('Before Detail')
        ->assertSee('After Detail')
        ->assertSee('venues');
});
