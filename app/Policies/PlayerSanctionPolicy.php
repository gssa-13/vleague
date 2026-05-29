<?php

// app/Policies/PlayerSanctionPolicy.php

namespace App\Policies;

use App\Models\PlayerSanction;
use App\Models\User;

class PlayerSanctionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('player-sanctions.view');
    }

    public function view(User $user, PlayerSanction $sanction): bool
    {
        return $user->hasPermissionTo('player-sanctions.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('player-sanctions.create');
    }

    public function update(User $user, PlayerSanction $sanction): bool
    {
        return $user->hasPermissionTo('player-sanctions.update');
    }

    public function delete(User $user, PlayerSanction $sanction): bool
    {
        return $user->hasPermissionTo('player-sanctions.delete');
    }

    public function restore(User $user, PlayerSanction $sanction): bool
    {
        return $user->hasPermissionTo('player-sanctions.restore');
    }
}
