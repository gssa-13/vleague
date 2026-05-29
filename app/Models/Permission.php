<?php

// app/Models/Permission.php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Custom Permission model extending Spatie's to support SoftDeletes.
 *
 * Registered in AppServiceProvider so Spatie uses this model instead
 * of its own. Soft-deleted permissions are excluded from all checks
 * by default, preserving the integrity of the permission system.
 */
class Permission extends SpatiePermission
{
    use SoftDeletes;
}
