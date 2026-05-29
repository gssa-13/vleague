<?php

// app/Enums/PriceStatus.php

namespace App\Enums;

enum PriceStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
