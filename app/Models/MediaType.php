<?php

// app/Models/MediaType.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaType extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'name',
        'sort_order',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
