<?php

// app/Listeners/LogRoleActivity.php

namespace App\Listeners;

use App\Events\RoleModified;

class LogRoleActivity
{
    public function __construct() {}

    /**
     * Handle the RoleModified event.
     * Clean extension point — add notifications, webhooks, etc. here in the future.
     */
    public function handle(RoleModified $event): void
    {
        // The ActivityLog record is already persisted by the LogsActivity trait.
        // This listener exists as an extension point for side effects
        // (e.g. notifications, audit exports) without modifying the trait.
        //
        // Example future use:
        // Notification::send(..., new RoleChangedNotification($event->activityLog));
    }
}
