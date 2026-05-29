<?php

// app/Services/ActivityLogService.php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepository;
use Illuminate\Database\Eloquent\Collection;

class ActivityLogService
{
    public function __construct(
        private readonly ActivityLogRepository $repository,
    ) {}

    public function log(array $data): ActivityLog
    {
        return $this->repository->create($data);
    }

    public function getLogsForRecord(string $model, int $recordId): Collection
    {
        return $this->repository->findByModel($model, $recordId);
    }

    public function getLogsForUser(int $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }
}
