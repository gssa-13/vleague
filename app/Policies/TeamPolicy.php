<?php

// app/Policies/TeamPolicy.php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('teams.view');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('teams.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('teams.create');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('teams.update');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('teams.delete');
    }

    public function restore(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('teams.restore');
    }

    public function assign(User $user): bool
    {
        return $user->hasPermissionTo('teams.assign');
    }
}
