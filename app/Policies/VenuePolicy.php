<?php

// app/Policies/VenuePolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;

class VenuePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('venues.view');
    }

    public function view(User $user, Venue $venue): bool
    {
        return $user->hasPermissionTo('venues.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('venues.create');
    }

    public function update(User $user, Venue $venue): bool
    {
        return $user->hasPermissionTo('venues.update');
    }

    public function delete(User $user, Venue $venue): bool
    {
        return $user->hasPermissionTo('venues.delete');
    }

    public function restore(User $user, Venue $venue): bool
    {
        return $user->hasPermissionTo('venues.restore');
    }
}
