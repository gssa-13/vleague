<?php

// app/Models/PlayerDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerDocument extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'legacy_id',
        'player_id',
        'type',
        'file_path',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
