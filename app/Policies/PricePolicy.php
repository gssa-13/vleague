<?php

// app/Policies/PricePolicy.php

namespace App\Policies;

use App\Models\Price;
use App\Models\User;

class PricePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('prices.view');
    }

    public function view(User $user, Price $price): bool
    {
        return $user->hasPermissionTo('prices.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('prices.create');
    }

    public function update(User $user, Price $price): bool
    {
        return $user->hasPermissionTo('prices.update');
    }

    public function delete(User $user, Price $price): bool
    {
        return $user->hasPermissionTo('prices.delete');
    }

    public function restore(User $user, Price $price): bool
    {
        return $user->hasPermissionTo('prices.restore');
    }
}
