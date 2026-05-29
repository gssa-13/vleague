<?php

// app/Policies/MediaPolicy.php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('media.view');
    }

    public function view(User $user, Media $media): bool
    {
        return $user->hasPermissionTo('media.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('media.create');
    }

    public function update(User $user, Media $media): bool
    {
        return $user->hasPermissionTo('media.update');
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->hasPermissionTo('media.delete');
    }

    public function restore(User $user, Media $media): bool
    {
        return $user->hasPermissionTo('media.restore');
    }
}
