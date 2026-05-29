<?php

// app/Enums/GameStatus.php

namespace App\Enums;

enum GameStatus: string
{
    case Scheduled = 'scheduled';
    case Played = 'played';
    case Cancelled = 'cancelled';
    case Postponed = 'postponed';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
