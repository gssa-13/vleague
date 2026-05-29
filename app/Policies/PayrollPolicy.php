<?php

// app/Policies/PayrollPolicy.php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;

class PayrollPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('payroll.view');
    }

    public function view(User $user, Payroll $payroll): bool
    {
        return $user->hasPermissionTo('payroll.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('payroll.create');
    }

    public function update(User $user, Payroll $payroll): bool
    {
        return $user->hasPermissionTo('payroll.update');
    }

    public function delete(User $user, Payroll $payroll): bool
    {
        return $user->hasPermissionTo('payroll.delete');
    }

    public function restore(User $user, Payroll $payroll): bool
    {
        return $user->hasPermissionTo('payroll.restore');
    }

    public function approve(User $user, Payroll $payroll): bool
    {
        return $user->hasPermissionTo('payroll.approve');
    }

    public function cancel(User $user, Payroll $payroll): bool
    {
        return $user->hasPermissionTo('payroll.cancel');
    }
}
