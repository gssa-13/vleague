<?php

// app/Http/Controllers/Scheduling/GameController.php

namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scheduling\CancelGameRequest;
use App\Http\Requests\Scheduling\CreateGameRequest;
use App\Http\Requests\Scheduling\UpdateGameRequest;
use App\Models\Competition;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private readonly GameService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Game::class);

        $games = $this->service->all();

        return view('scheduling.index', compact('games'));
    }

    public function create(): View
    {
        $this->authorize('create', Game::class);

        $competitions = Competition::all();

        return view('scheduling.create', compact('competitions'));
    }

    public function store(CreateGameRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['scheduled_at' => $e->getMessage()])->withInput();
        }

        return redirect()->route('games.index')
            ->with('success', 'Game created successfully.');
    }

    public function edit(Game $game): View
    {
        $this->authorize('update', $game);

        $competitions = Competition::all();

        return view('scheduling.edit', compact('game', 'competitions'));
    }

    public function update(UpdateGameRequest $request, Game $game): RedirectResponse
    {
        $this->service->update($game, $request->validated());

        return redirect()->route('games.index')
            ->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game): RedirectResponse
    {
        $this->authorize('delete', $game);

        $this->service->delete($game);

        return redirect()->route('games.index')
            ->with('success', 'Game deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $game = $this->service->findTrashedById($id);

        $this->authorize('restore', $game);

        $this->service->restore($game);

        return redirect()->route('games.index')
            ->with('success', 'Game restored successfully.');
    }

    public function cancel(CancelGameRequest $request, Game $game): RedirectResponse
    {
        $this->service->cancel($game, (int) $request->validated('cancellation_reason_id'));

        return redirect()->route('games.index')
            ->with('success', 'Game cancelled successfully.');
    }
}
