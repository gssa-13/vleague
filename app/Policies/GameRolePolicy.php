<?php

// app/Policies/GameRolePolicy.php

namespace App\Policies;

use App\Models\GameRole;
use App\Models\User;

class GameRolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('game-roles.view');
    }

    public function view(User $user, GameRole $gameRole): bool
    {
        return $user->hasPermissionTo('game-roles.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('game-roles.create');
    }

    public function update(User $user, GameRole $gameRole): bool
    {
        return $user->hasPermissionTo('game-roles.update');
    }

    public function delete(User $user, GameRole $gameRole): bool
    {
        return $user->hasPermissionTo('game-roles.delete');
    }

    public function restore(User $user, GameRole $gameRole): bool
    {
        return $user->hasPermissionTo('game-roles.restore');
    }

    public function assign(User $user): bool
    {
        return $user->hasPermissionTo('game-roles.assign');
    }
}
