<?php

// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->createActivityLog(
                action: 'created',
                columnName: null,
                oldValue: null,
                newValue: json_encode($model->getAttributes()),
            );
        });

        static::updated(function ($model) {
            $skipColumns = ['updated_at', 'created_at', 'deleted_at'];
            $dirty = $model->getDirty();

            foreach ($dirty as $column => $newValue) {
                if (in_array($column, $skipColumns, true)) {
                    continue;
                }

                $model->createActivityLog(
                    action: 'updated',
                    columnName: $column,
                    oldValue: $model->getOriginal($column),
                    newValue: $newValue,
                );
            }
        });

        static::deleted(function ($model) {
            $isSoftDelete = in_array(SoftDeletes::class, class_uses_recursive($model), true);

            $model->createActivityLog(
                action: 'deleted',
                columnName: null,
                oldValue: json_encode($model->getOriginal()),
                newValue: null,
                forcedUseSoftDeletes: $isSoftDelete,
            );
        });

        // Only register restored listener when the model uses SoftDeletes
        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored(function ($model) {
                $model->createActivityLog(
                    action: 'restored',
                    columnName: null,
                    oldValue: null,
                    newValue: json_encode($model->getAttributes()),
                );
            });
        }
    }

    private function createActivityLog(
        string $action,
        ?string $columnName,
        mixed $oldValue,
        mixed $newValue,
        bool $forcedUseSoftDeletes = false,
    ): void {
        ActivityLog::create([
            'action' => $action,
            'model' => static::class,
            'table_name' => $this->getTable(),
            'record_id' => $this->getKey(),
            'column_name' => $columnName,
            'old_value' => $oldValue !== null ? (string) $oldValue : null,
            'new_value' => $newValue !== null ? (string) $newValue : null,
            'user_id' => Auth::id(),
            'user_ip' => Request::ip(),
        ]);
    }
}
