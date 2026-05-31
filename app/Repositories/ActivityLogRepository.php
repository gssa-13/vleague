<?php

// app/Repositories/ActivityLogRepository.php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;

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

    public function findById(int $id): ?ActivityLog
    {
        return ActivityLog::with('user')->find($id);
    }

    public function search(array $filters = []): Collection
    {
        $query = ActivityLog::with('user');

        if (! empty($filters['model'])) {
            $query->where('model', $filters['model']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        if (! empty($filters['from'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['from'])->startOfDay());
        }

        if (! empty($filters['to'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['to'])->endOfDay());
        }

        return $query
            ->latest('created_at')
            ->latest('id')
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

    public function availableModels(): SupportCollection
    {
        return ActivityLog::query()
            ->select('model')
            ->distinct()
            ->orderBy('model')
            ->pluck('model');
    }

    public function availableUsers(): Collection
    {
        return User::query()
            ->whereIn('id', ActivityLog::query()
                ->select('user_id')
                ->whereNotNull('user_id'))
            ->orderBy('name')
            ->get();
    }

    public function onlyTrashed(): Collection
    {
        return ActivityLog::onlyTrashed()->get();
    }
}
