<?php

// app/Models/Player.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'email_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'email_verified_at' => 'datetime',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PlayerDocument::class);
    }
}
