<?php

// app/Models/Payment.php

namespace App\Models;

use App\Enums\PaymentConcept;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'competition_id',
        'competition_team_id',
        'concept',
        'payment_method',
        'status',
        'amount',
        'paid_amount',
        'debt',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'debt' => 'decimal:2',
            'concept' => PaymentConcept::class,
            'payment_method' => PaymentMethod::class,
            'status' => PaymentStatus::class,
        ];
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function competitionTeam(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(PaymentEntry::class);
    }

    public function cancellations(): HasMany
    {
        return $this->hasMany(PaymentCancellation::class);
    }
}
