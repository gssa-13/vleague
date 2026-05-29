<?php

// app/Models/Media.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $table = 'media';

    protected $fillable = [
        'legacy_id',
        'media_type_id',
        'name',
        'file_path',
    ];

    public function mediaType(): BelongsTo
    {
        return $this->belongsTo(MediaType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
