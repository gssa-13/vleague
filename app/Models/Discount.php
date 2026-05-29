<?php

// app/Models/Discount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'legacy_id',
        'price_id',
        'percentage',
        'valid_from',
        'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_until' => 'date',
        ];
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class);
    }
}
