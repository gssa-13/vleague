<?php

// app/Models/BaseModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Base model for all vl League domain models.
 *
 * Enforces SoftDeletes on every model that extends this class.
 * No physical deletions are allowed — all removals must be logical.
 */
abstract class BaseModel extends Model
{
    use SoftDeletes;
}
