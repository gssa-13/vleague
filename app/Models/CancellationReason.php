<?php

// app/Models/CancellationReason.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CancellationReason extends BaseModel
{
    use HasFactory;

    protected $fillable = ['legacy_id', 'name'];
}
