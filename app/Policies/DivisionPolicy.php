<?php

// app/Policies/DivisionPolicy.php

namespace App\Policies;

use App\Models\Division;
use App\Models\User;

class DivisionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('divisions.view');
    }

    public function view(User $user, Division $division): bool
    {
        return $user->hasPermissionTo('divisions.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('divisions.create');
    }

    public function update(User $user, Division $division): bool
    {
        return $user->hasPermissionTo('divisions.update');
    }

    public function delete(User $user, Division $division): bool
    {
        return $user->hasPermissionTo('divisions.delete');
    }

    public function restore(User $user, Division $division): bool
    {
        return $user->hasPermissionTo('divisions.restore');
    }
}
