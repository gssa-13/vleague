<?php

// app/Enums/GameType.php

namespace App\Enums;

enum GameType: string
{
    case Match = 'match';
    case Practice = 'practice';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
