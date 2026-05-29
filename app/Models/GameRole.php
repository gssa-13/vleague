<?php

// app/Models/GameRole.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameRole extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'name',
        'description',
    ];

    public function templates(): HasMany
    {
        return $this->hasMany(GameRoleTemplate::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(GameRoleAssignment::class);
    }
}
