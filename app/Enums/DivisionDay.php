<?php

// app/Enums/DivisionDay.php

namespace App\Enums;

enum DivisionDay: string
{
    case Monday = 'monday';
    case Tuesday = 'tuesday';
    case Wednesday = 'wednesday';
    case Thursday = 'thursday';
    case Friday = 'friday';
    case Saturday = 'saturday';
    case Sunday = 'sunday';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
