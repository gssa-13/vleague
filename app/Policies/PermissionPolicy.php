<?php

// app/Policies/PermissionPolicy.php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('permissions.view');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permissions.view');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permissions.delete');
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permissions.restore');
    }
}
