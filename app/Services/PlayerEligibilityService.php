<?php

// app/Services/PlayerEligibilityService.php

namespace App\Services;

use App\Models\PlayerSanction;
use App\Repositories\PlayerSanctionRepository;

class PlayerEligibilityService
{
    public function __construct(
        private readonly PlayerSanctionRepository $repository,
    ) {}

    /**
     * A player is eligible when they have no active sanction pending games.
     * Soft-deleted sanctions are excluded by the repository.
     */
    public function isEligible(int $playerId): bool
    {
        $sanctions = $this->repository->activeForPlayer($playerId);

        foreach ($sanctions as $sanction) {
            if (! $this->isSanctionServed($sanction)) {
                return false;
            }
        }

        return true;
    }

    private function isSanctionServed(PlayerSanction $sanction): bool
    {
        return $sanction->served_games >= $sanction->sanctioned_games;
    }
}
