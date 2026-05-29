<?php

// app/Models/Division.php

namespace App\Models;

use App\Enums\DivisionDay;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Division extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'tournament_id',
        'name',
        'day',
        'field_number',
        'group_letter',
    ];

    protected function casts(): array
    {
        return [
            'day' => DivisionDay::class,
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
