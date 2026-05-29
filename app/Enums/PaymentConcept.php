<?php

// app/Enums/PaymentConcept.php

namespace App\Enums;

enum PaymentConcept: string
{
    case Inscription = 'inscription';
    case Deposit = 'deposit';
    case Arbitration = 'arbitration';
    case Registry = 'registry';
    case Schedule = 'schedule';
    case ExtraIncome = 'extra_income';

    public function label(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }
}
