<?php

// app/Http/Controllers/GameRoles/GameRoleAssignmentController.php

namespace App\Http\Controllers\GameRoles;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameRoles\AssignGameRoleRequest;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\GameRoleAssignment;
use App\Services\GameRoleService;
use Illuminate\Http\RedirectResponse;

class GameRoleAssignmentController extends Controller
{
    public function __construct(
        private readonly GameRoleService $service,
    ) {}

    public function store(AssignGameRoleRequest $request, Game $game): RedirectResponse
    {
        $this->service->assign($game->id, $request->validated());

        return redirect()->back()
            ->with('success', 'Role assigned to game successfully.');
    }

    public function destroy(Game $game, GameRoleAssignment $assignment): RedirectResponse
    {
        $this->authorize('assign', GameRole::class);

        $this->service->revoke($assignment);

        return redirect()->back()
            ->with('success', 'Role assignment revoked successfully.');
    }
}
