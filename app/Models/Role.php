<?php

// app/Models/Role.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use LogsActivity;
    use SoftDeletes;

    // Spatie's $fillable is inherited and extended here to include nothing extra.
    // Do NOT redeclare $fillable — Spatie manages it internally via its contract.
    // SoftDeletes adds deleted_at handling transparently.
}
