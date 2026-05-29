<?php

// app/Policies/PlayerPolicy.php

namespace App\Policies;

use App\Models\Player;
use App\Models\User;

class PlayerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('players.view');
    }

    public function view(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('players.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('players.create');
    }

    public function update(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('players.update');
    }

    public function delete(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('players.delete');
    }

    public function restore(User $user, Player $player): bool
    {
        return $user->hasPermissionTo('players.restore');
    }
}
