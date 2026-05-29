<?php

// app/Models/Team.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'name',
    ];

    public function competitionTeams(): HasMany
    {
        return $this->hasMany(CompetitionTeam::class);
    }
}
