<?php

// app/Models/PlayerSanction.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerSanction extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'player_id',
        'competition_team_id',
        'competition_id',
        'reason',
        'sanctioned_games',
        'served_games',
    ];

    protected function casts(): array
    {
        return [
            'sanctioned_games' => 'integer',
            'served_games' => 'integer',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function competitionTeam(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * A sanction is fully served when served_games reaches sanctioned_games.
     */
    public function isServed(): bool
    {
        return $this->served_games >= $this->sanctioned_games;
    }
}
