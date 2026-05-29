<?php

// app/Enums/TournamentStatus.php

namespace App\Enums;

enum TournamentStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Finished = 'finished';
}
