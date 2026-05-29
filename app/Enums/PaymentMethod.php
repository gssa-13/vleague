<?php

// app/Enums/PaymentMethod.php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Transfer = 'transfer';
    case Card = 'card';
    case Check = 'check';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
