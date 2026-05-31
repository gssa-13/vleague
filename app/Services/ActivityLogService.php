<?php

// app/Services/ActivityLogService.php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

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

    public function search(array $filters = []): Collection
    {
        return $this->repository->search($filters);
    }

    public function findById(int $id): ?ActivityLog
    {
        return $this->repository->findById($id);
    }

    public function availableModels(): SupportCollection
    {
        return $this->repository->availableModels();
    }

    public function availableUsers(): Collection
    {
        return $this->repository->availableUsers();
    }

    public function exportRows(array $filters = []): array
    {
        return $this->search($filters)
            ->map(fn (ActivityLog $log) => $this->formatExportRow($log))
            ->values()
            ->all();
    }

    private function formatExportRow(ActivityLog $log): array
    {
        return [
            'id' => $log->getKey(),
            'action' => $log->action,
            'model' => $log->model,
            'table_name' => $log->table_name,
            'record_id' => $log->record_id,
            'column_name' => $log->column_name,
            'old_value' => $log->old_value,
            'new_value' => $log->new_value,
            'user_id' => $log->user_id,
            'user' => $this->formatUser($log->user),
            'user_ip' => $log->user_ip,
            'created_at' => $log->created_at?->toDateTimeString(),
        ];
    }

    private function formatUser(?object $user): ?array
    {
        if ($user === null) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
