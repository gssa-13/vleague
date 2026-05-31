<?php

// app/Policies/ReportPolicy.php

namespace App\Policies;

use App\Models\User;

/**
 * Reports are not backed by a model, so this policy is registered as
 * named gates (report.view / report.export) in AppServiceProvider.
 */
class ReportPolicy
{
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('reports.view');
    }

    public function export(User $user): bool
    {
        return $user->hasPermissionTo('reports.export');
    }
}
