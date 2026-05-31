<?php

namespace App\Policies;

use App\Models\User;

class AuditPolicy
{
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('audit.view');
    }

    public function export(User $user): bool
    {
        return $user->hasPermissionTo('audit.export');
    }
}
