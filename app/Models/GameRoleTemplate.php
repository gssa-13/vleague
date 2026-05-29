<?php

// app/Models/GameRoleTemplate.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameRoleTemplate extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'competition_id',
        'game_role_id',
        'required_count',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function gameRole(): BelongsTo
    {
        return $this->belongsTo(GameRole::class);
    }
}
