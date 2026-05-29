<?php

// app/Models/EmployeeDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'legacy_id',
        'employee_id',
        'type',
        'file_path',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
