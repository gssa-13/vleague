<?php

// app/Policies/NavigationPolicy.php

namespace App\Policies;

use App\Models\NavigationItem;
use App\Models\User;

class NavigationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('navigation.view');
    }

    public function view(User $user, NavigationItem $item): bool
    {
        return $user->hasPermissionTo('navigation.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('navigation.create');
    }

    public function update(User $user, NavigationItem $item): bool
    {
        return $user->hasPermissionTo('navigation.update');
    }

    public function delete(User $user, NavigationItem $item): bool
    {
        return $user->hasPermissionTo('navigation.delete');
    }

    public function restore(User $user, NavigationItem $item): bool
    {
        return $user->hasPermissionTo('navigation.restore');
    }
}
