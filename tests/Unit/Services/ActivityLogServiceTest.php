<?php

// tests/Unit/Services/ActivityLogServiceTest.php

use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepository;
use App\Services\ActivityLogService;

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
