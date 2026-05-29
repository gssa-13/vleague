<?php

// app/Enums/PayrollAdjustmentType.php

namespace App\Enums;

enum PayrollAdjustmentType: string
{
    case Addition = 'addition';
    case Deduction = 'deduction';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
