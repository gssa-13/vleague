<?php

// tests/Unit/Services/ActivityLogServiceTest.php

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Venue;
use App\Repositories\ActivityLogRepository;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->repository = Mockery::mock(ActivityLogRepository::class);
    $this->service = new ActivityLogService($this->repository);
});

it('delegates log creation to the repository', function () {
    $data = [
        'action' => 'created',
        'model' => 'App\\Models\\Role',
        'table_name' => 'roles',
        'record_id' => 1,
        'user_id' => null,
        'user_ip' => '127.0.0.1',
    ];

    $log = new ActivityLog($data);

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($log);

    $result = $this->service->log($data);

    expect($result)->toBeInstanceOf(ActivityLog::class);
});

it('resolves user_id and user_ip when logging', function () {
    // Simulate what the trait does: read Auth::id() and Request::ip() at call time
    $userId = 42;
    $userIp = '192.168.1.1';

    $data = [
        'action' => 'updated',
        'model' => 'App\\Models\\Role',
        'table_name' => 'roles',
        'record_id' => 5,
        'column_name' => 'name',
        'old_value' => 'Admin',
        'new_value' => 'Super Admin',
        'user_id' => $userId,
        'user_ip' => $userIp,
    ];

    $log = new ActivityLog($data);

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($log);

    $result = $this->service->log($data);

    expect($result->user_id)->toBe(42)
        ->and($result->user_ip)->toBe('192.168.1.1');
});

it('delegates filtered audit searches to the repository', function () {
    $filters = ['model' => Venue::class, 'user_id' => 5];
    $logs = new Collection([
        new ActivityLog(['model' => Venue::class, 'user_id' => 5]),
    ]);

    $this->repository
        ->shouldReceive('search')
        ->once()
        ->with($filters)
        ->andReturn($logs);

    expect($this->service->search($filters))->toBe($logs);
});

it('formats audit export rows with related user data', function () {
    $user = new User(['name' => 'Audit Exporter', 'email' => 'audit@example.test']);
    $user->id = 9;

    $log = new ActivityLog([
        'action' => 'updated',
        'model' => Venue::class,
        'table_name' => 'venues',
        'record_id' => 44,
        'column_name' => 'name',
        'old_value' => 'Before Export',
        'new_value' => 'After Export',
        'user_id' => $user->id,
        'user_ip' => '127.0.0.1',
    ]);
    $log->id = 3;
    $log->created_at = Carbon::parse('2026-05-15 10:30:00');
    $log->setRelation('user', $user);

    $this->repository
        ->shouldReceive('search')
        ->once()
        ->with([])
        ->andReturn(new Collection([$log]));

    expect($this->service->exportRows([]))->toBe([
        [
            'id' => 3,
            'action' => 'updated',
            'model' => Venue::class,
            'table_name' => 'venues',
            'record_id' => 44,
            'column_name' => 'name',
            'old_value' => 'Before Export',
            'new_value' => 'After Export',
            'user_id' => 9,
            'user' => [
                'id' => 9,
                'name' => 'Audit Exporter',
                'email' => 'audit@example.test',
            ],
            'user_ip' => '127.0.0.1',
            'created_at' => '2026-05-15 10:30:00',
        ],
    ]);
});
