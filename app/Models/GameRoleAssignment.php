<?php

// app/Models/GameRoleAssignment.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameRoleAssignment extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'game_id',
        'game_role_id',
        'employee_id',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gameRole(): BelongsTo
    {
        return $this->belongsTo(GameRole::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
