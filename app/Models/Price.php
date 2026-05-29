<?php

// app/Models/Price.php

namespace App\Models;

use App\Enums\PriceStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Price extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'name',
        'amount',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => PriceStatus::class,
        ];
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
}
