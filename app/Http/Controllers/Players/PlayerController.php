<?php

// app/Http/Controllers/Players/PlayerController.php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Http\Requests\Players\CreatePlayerRequest;
use App\Http\Requests\Players\UpdatePlayerRequest;
use App\Models\Player;
use App\Services\PlayerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlayerController extends Controller
{
    public function __construct(
        private readonly PlayerService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Player::class);

        $players = $this->service->all();

        return view('players.index', compact('players'));
    }

    public function create(): View
    {
        $this->authorize('create', Player::class);

        return view('players.create');
    }

    public function store(CreatePlayerRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }

        return redirect()->route('players.index')
            ->with('success', 'Player created successfully.');
    }

    public function edit(Player $player): View
    {
        $this->authorize('update', $player);

        return view('players.edit', compact('player'));
    }

    public function update(UpdatePlayerRequest $request, Player $player): RedirectResponse
    {
        $this->service->update($player, $request->validated());

        return redirect()->route('players.index')
            ->with('success', 'Player updated successfully.');
    }

    public function destroy(Player $player): RedirectResponse
    {
        $this->authorize('delete', $player);

        $this->service->delete($player);

        return redirect()->route('players.index')
            ->with('success', 'Player deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $player = $this->service->findTrashedById($id);

        $this->authorize('restore', $player);

        $this->service->restore($player);

        return redirect()->route('players.index')
            ->with('success', 'Player restored successfully.');
    }
}
