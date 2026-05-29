<?php

// app/Models/Game.php

namespace App\Models;

use App\Enums\GameStatus;
use App\Enums\GameType;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'competition_id',
        'home_team_id',
        'away_team_id',
        'game_type',
        'scheduled_at',
        'field_number',
        'status',
        'home_score',
        'away_score',
        'matchday',
        'cancellation_reason_id',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'game_type' => GameType::class,
            'status' => GameStatus::class,
        ];
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class, 'away_team_id');
    }

    public function cancellationReason(): BelongsTo
    {
        return $this->belongsTo(CancellationReason::class);
    }
}
