<?php

// app/Models/TeamRoster.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamRoster extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'competition_team_id',
        'player_id',
        'jersey_number',
        'is_captain',
        'is_wildcard',
    ];

    protected function casts(): array
    {
        return [
            'is_captain' => 'boolean',
            'is_wildcard' => 'boolean',
        ];
    }

    public function competitionTeam(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
