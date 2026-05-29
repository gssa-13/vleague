<?php

// app/Models/PaymentCancellation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentCancellation extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'legacy_id',
        'payment_id',
        'cancelled_by',
        'reason',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
