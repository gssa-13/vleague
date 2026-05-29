<?php

// app/Events/RoleModified.php

namespace App\Events;

use App\Models\ActivityLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleModified
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ActivityLog $activityLog,
    ) {}
}
