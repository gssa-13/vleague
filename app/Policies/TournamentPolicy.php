<?php

// app/Policies/TournamentPolicy.php

namespace App\Policies;

use App\Models\Tournament;
use App\Models\User;

class TournamentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('tournaments.view');
    }

    public function view(User $user, Tournament $tournament): bool
    {
        return $user->hasPermissionTo('tournaments.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('tournaments.create');
    }

    public function update(User $user, Tournament $tournament): bool
    {
        return $user->hasPermissionTo('tournaments.update');
    }

    public function delete(User $user, Tournament $tournament): bool
    {
        return $user->hasPermissionTo('tournaments.delete');
    }

    public function restore(User $user, Tournament $tournament): bool
    {
        return $user->hasPermissionTo('tournaments.restore');
    }
}
