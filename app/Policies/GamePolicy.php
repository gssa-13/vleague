<?php

// app/Policies/GamePolicy.php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('games.view');
    }

    public function view(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('games.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('games.create');
    }

    public function update(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('games.update');
    }

    public function delete(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('games.delete');
    }

    public function restore(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('games.restore');
    }

    public function cancel(User $user, Game $game): bool
    {
        return $user->hasPermissionTo('games.cancel');
    }
}
