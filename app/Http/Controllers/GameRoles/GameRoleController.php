<?php

// app/Http/Controllers/GameRoles/GameRoleController.php

namespace App\Http\Controllers\GameRoles;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameRoles\CreateGameRoleRequest;
use App\Http\Requests\GameRoles\UpdateGameRoleRequest;
use App\Models\GameRole;
use App\Services\GameRoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameRoleController extends Controller
{
    public function __construct(
        private readonly GameRoleService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', GameRole::class);

        $gameRoles = $this->service->all();

        return view('game-roles.index', compact('gameRoles'));
    }

    public function create(): View
    {
        $this->authorize('create', GameRole::class);

        return view('game-roles.create');
    }

    public function store(CreateGameRoleRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('game-roles.index')
            ->with('success', 'Game role created successfully.');
    }

    public function edit(GameRole $gameRole): View
    {
        $this->authorize('update', $gameRole);

        return view('game-roles.edit', compact('gameRole'));
    }

    public function update(UpdateGameRoleRequest $request, GameRole $gameRole): RedirectResponse
    {
        $this->service->update($gameRole, $request->validated());

        return redirect()->route('game-roles.index')
            ->with('success', 'Game role updated successfully.');
    }

    public function destroy(GameRole $gameRole): RedirectResponse
    {
        $this->authorize('delete', $gameRole);

        $this->service->delete($gameRole);

        return redirect()->route('game-roles.index')
            ->with('success', 'Game role deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $gameRole = $this->service->findTrashedById($id);

        $this->authorize('restore', $gameRole);

        $this->service->restore($gameRole);

        return redirect()->route('game-roles.index')
            ->with('success', 'Game role restored successfully.');
    }
}
