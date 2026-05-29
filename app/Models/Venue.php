<?php

// app/Models/Venue.php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'name',
        'address',
        'city',
        'max_fields',
        'match_duration_minutes',
        'advance_booking_days',
    ];
}
