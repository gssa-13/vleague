<?php

// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users.view');
    }

    public function view(User $user, User $target): bool
    {
        return $user->hasPermissionTo('users.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users.create');
    }

    public function update(User $user, User $target): bool
    {
        return $user->hasPermissionTo('users.update');
    }

    public function delete(User $user, User $target): bool
    {
        return $user->hasPermissionTo('users.delete');
    }

    public function restore(User $user, User $target): bool
    {
        return $user->hasPermissionTo('users.restore');
    }

    public function assignRole(User $user): bool
    {
        return $user->hasPermissionTo('roles.assign');
    }

    public function assignPermission(User $user): bool
    {
        return $user->hasPermissionTo('permissions.assign');
    }
}
