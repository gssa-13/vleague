<?php

// app/Models/PayrollAdjustment.php

namespace App\Models;

use App\Enums\PayrollAdjustmentType;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollAdjustment extends BaseModel
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'legacy_id',
        'employee_payroll_id',
        'type',
        'amount',
        'concept',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type' => PayrollAdjustmentType::class,
        ];
    }

    public function employeePayroll(): BelongsTo
    {
        return $this->belongsTo(EmployeePayroll::class);
    }
}
