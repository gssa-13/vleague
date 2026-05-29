<?php

// tests/Feature/Role/RoleActivityLogTest.php

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('creates a log entry when a role is created', function () {
    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    expect(ActivityLog::where('action', 'created')
        ->where('model', Role::class)
        ->where('record_id', $role->id)
        ->exists()
    )->toBeTrue();
});

it('creates one log entry per modified column when a role is updated', function () {
    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    // Clear logs from creation
    ActivityLog::query()->delete();

    $role->update(['name' => 'super-editor']);

    $logs = ActivityLog::where('action', 'updated')
        ->where('model', Role::class)
        ->where('record_id', $role->id)
        ->get();

    // Only 'name' changed — one log entry expected
    expect($logs)->toHaveCount(1)
        ->and($logs->first()->column_name)->toBe('name')
        ->and($logs->first()->old_value)->toBe('editor')
        ->and($logs->first()->new_value)->toBe('super-editor');
});

it('creates a log entry when a role is soft deleted', function () {
    $role = Role::create(['name' => 'moderator', 'guard_name' => 'web']);

    ActivityLog::query()->delete();

    $role->delete();

    expect(ActivityLog::where('action', 'deleted')
        ->where('model', Role::class)
        ->where('record_id', $role->id)
        ->exists()
    )->toBeTrue();
});

it('creates a log entry when a role is restored', function () {
    $role = Role::create(['name' => 'viewer', 'guard_name' => 'web']);
    $role->delete();

    ActivityLog::query()->delete();

    $role->restore();

    expect(ActivityLog::where('action', 'restored')
        ->where('model', Role::class)
        ->where('record_id', $role->id)
        ->exists()
    )->toBeTrue();
});

it('does not permanently delete a role on delete()', function () {
    $role = Role::create(['name' => 'guest', 'guard_name' => 'web']);
    $roleId = $role->id;

    $role->delete();

    // Record still exists in DB (soft deleted)
    expect(Role::withTrashed()->find($roleId))->not->toBeNull()
        ->and(Role::withTrashed()->find($roleId)->deleted_at)->not->toBeNull();
});

it('does not permanently delete a log entry on delete()', function () {
    $user = User::factory()->create();

    $log = ActivityLog::factory()->create(['user_id' => $user->id]);
    $logId = $log->id;

    $log->delete();

    // Log still exists in DB (soft deleted)
    expect(ActivityLog::withTrashed()->find($logId))->not->toBeNull()
        ->and(ActivityLog::withTrashed()->find($logId)->deleted_at)->not->toBeNull();
});
