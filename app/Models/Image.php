<?php

// app/Models/Image.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'media_id',
        'url',
        'link',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
