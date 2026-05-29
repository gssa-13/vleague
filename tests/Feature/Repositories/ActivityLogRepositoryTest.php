<?php

// tests/Unit/Repositories/ActivityLogRepositoryTest.php

use App\Models\ActivityLog;
use App\Models\User;
use App\Repositories\ActivityLogRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ActivityLogRepository;
});

it('creates an activity log record', function () {
    $user = User::factory()->create();

    $data = [
        'action' => 'created',
        'model' => 'App\\Models\\Role',
        'table_name' => 'roles',
        'record_id' => 1,
        'column_name' => null,
        'old_value' => null,
        'new_value' => json_encode(['name' => 'Admin', 'guard_name' => 'web']),
        'user_id' => $user->id,
        'user_ip' => '127.0.0.1',
    ];

    $log = $this->repository->create($data);

    expect($log)->toBeInstanceOf(ActivityLog::class)
        ->and($log->action)->toBe('created')
        ->and($log->model)->toBe('App\\Models\\Role')
        ->and($log->record_id)->toBe(1);
});

it('finds logs by model and record id', function () {
    $user = User::factory()->create();

    ActivityLog::factory()->create([
        'model' => 'App\\Models\\Role',
        'record_id' => 10,
        'user_id' => $user->id,
    ]);

    ActivityLog::factory()->create([
        'model' => 'App\\Models\\Role',
        'record_id' => 99,
        'user_id' => $user->id,
    ]);

    $results = $this->repository->findByModel('App\\Models\\Role', 10);

    expect($results)->toHaveCount(1)
        ->and($results->first()->record_id)->toBe(10);
});

it('finds logs by user id', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    ActivityLog::factory()->count(3)->create(['user_id' => $userA->id]);
    ActivityLog::factory()->count(2)->create(['user_id' => $userB->id]);

    $results = $this->repository->findByUser($userA->id);

    expect($results)->toHaveCount(3)
        ->and($results->every(fn ($log) => $log->user_id === $userA->id))->toBeTrue();
});

it('returns only trashed logs with onlyTrashed', function () {
    $user = User::factory()->create();

    $active = ActivityLog::factory()->create(['user_id' => $user->id]);
    $trashed = ActivityLog::factory()->create(['user_id' => $user->id]);
    $trashed->delete();

    $results = $this->repository->onlyTrashed();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($trashed->id);
});
