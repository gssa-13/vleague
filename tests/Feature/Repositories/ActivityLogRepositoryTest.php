<?php

// tests/Unit/Repositories/ActivityLogRepositoryTest.php

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
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

it('searches active logs by model user and date range', function () {
    $actor = User::factory()->create();
    $otherActor = User::factory()->create();

    ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $actor->id,
        'new_value' => 'Matching repository log',
        'created_at' => '2026-05-15 10:00:00',
    ]);

    ActivityLog::factory()->create([
        'model' => Role::class,
        'table_name' => 'roles',
        'user_id' => $actor->id,
        'new_value' => 'Wrong repository model',
        'created_at' => '2026-05-15 10:00:00',
    ]);

    ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $otherActor->id,
        'new_value' => 'Wrong repository user',
        'created_at' => '2026-05-15 10:00:00',
    ]);

    $trashedLog = ActivityLog::factory()->create([
        'model' => Venue::class,
        'table_name' => 'venues',
        'user_id' => $actor->id,
        'new_value' => 'Trashed repository log',
        'created_at' => '2026-05-15 10:00:00',
    ]);
    $trashedLog->delete();

    $results = $this->repository->search([
        'model' => Venue::class,
        'user_id' => $actor->id,
        'from' => '2026-05-01',
        'to' => '2026-05-31',
    ]);

    expect($results)->toHaveCount(1)
        ->and($results->first()->new_value)->toBe('Matching repository log');
});

it('finds an active log by id with its user', function () {
    $user = User::factory()->create(['name' => 'Repository User']);
    $log = ActivityLog::factory()->create(['user_id' => $user->id]);

    $result = $this->repository->findById($log->id);

    expect($result)->not->toBeNull()
        ->and($result->is($log))->toBeTrue()
        ->and($result->user->name)->toBe('Repository User');
});
