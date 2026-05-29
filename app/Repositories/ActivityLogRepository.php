<?php

// app/Repositories/ActivityLogRepository.php

namespace App\Repositories;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Collection;

class ActivityLogRepository
{
    public function create(array $data): ActivityLog
    {
        return ActivityLog::create($data);
    }

    public function findByModel(string $model, int $recordId): Collection
    {
        return ActivityLog::where('model', $model)
            ->where('record_id', $recordId)
            ->get();
    }

    public function findByUser(int $userId): Collection
    {
        return ActivityLog::where('user_id', $userId)->get();
    }

    public function allWithTrashed(): Collection
    {
        return ActivityLog::withTrashed()->get();
    }

    public function onlyTrashed(): Collection
    {
        return ActivityLog::onlyTrashed()->get();
    }
}
