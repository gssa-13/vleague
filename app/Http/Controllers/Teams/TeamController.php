<?php

// app/Http/Controllers/Teams/TeamController.php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamRequest;
use App\Http\Requests\Teams\UpdateTeamRequest;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(
        private readonly TeamService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Team::class);

        $teams = $this->service->all();

        return view('teams.index', compact('teams'));
    }

    public function create(): View
    {
        $this->authorize('create', Team::class);

        return view('teams.create');
    }

    public function store(CreateTeamRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully.');
    }

    public function edit(Team $team): View
    {
        $this->authorize('update', $team);

        return view('teams.edit', compact('team'));
    }

    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $this->service->update($team, $request->validated());

        return redirect()->route('teams.index')
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        $this->service->delete($team);

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $team = $this->service->findTrashedById($id);

        $this->authorize('restore', $team);

        $this->service->restore($team);

        return redirect()->route('teams.index')
            ->with('success', 'Team restored successfully.');
    }
}
