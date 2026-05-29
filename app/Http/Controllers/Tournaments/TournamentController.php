<?php

// app/Http/Controllers/Tournaments/TournamentController.php

namespace App\Http\Controllers\Tournaments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tournaments\CreateTournamentRequest;
use App\Http\Requests\Tournaments\UpdateTournamentRequest;
use App\Models\Tournament;
use App\Models\Venue;
use App\Services\TournamentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function __construct(
        private readonly TournamentService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Tournament::class);

        $tournaments = $this->service->all();

        return view('tournaments.index', compact('tournaments'));
    }

    public function create(): View
    {
        $this->authorize('create', Tournament::class);

        $venues = Venue::all();

        return view('tournaments.create', compact('venues'));
    }

    public function store(CreateTournamentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('tournaments.index')
            ->with('success', 'Tournament created successfully.');
    }

    public function edit(Tournament $tournament): View
    {
        $this->authorize('update', $tournament);

        $venues = Venue::all();

        return view('tournaments.edit', compact('tournament', 'venues'));
    }

    public function update(UpdateTournamentRequest $request, Tournament $tournament): RedirectResponse
    {
        $this->service->update($tournament, $request->validated());

        return redirect()->route('tournaments.index')
            ->with('success', 'Tournament updated successfully.');
    }

    public function destroy(Tournament $tournament): RedirectResponse
    {
        $this->authorize('delete', $tournament);

        $this->service->delete($tournament);

        return redirect()->route('tournaments.index')
            ->with('success', 'Tournament deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $tournament = $this->service->findTrashedById($id);

        $this->authorize('restore', $tournament);

        $this->service->restore($tournament);

        return redirect()->route('tournaments.index')
            ->with('success', 'Tournament restored successfully.');
    }
}
