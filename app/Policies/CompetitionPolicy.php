<?php

// app/Policies/CompetitionPolicy.php

namespace App\Policies;

use App\Models\Competition;
use App\Models\User;

class CompetitionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('competitions.view');
    }

    public function view(User $user, Competition $competition): bool
    {
        return $user->hasPermissionTo('competitions.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('competitions.create');
    }

    public function delete(User $user, Competition $competition): bool
    {
        return $user->hasPermissionTo('competitions.delete');
    }

    public function restore(User $user, Competition $competition): bool
    {
        return $user->hasPermissionTo('competitions.restore');
    }
}
