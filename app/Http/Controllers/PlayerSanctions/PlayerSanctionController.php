<?php

// app/Http/Controllers/PlayerSanctions/PlayerSanctionController.php

namespace App\Http\Controllers\PlayerSanctions;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerSanctions\CreatePlayerSanctionRequest;
use App\Http\Requests\PlayerSanctions\UpdatePlayerSanctionRequest;
use App\Models\Player;
use App\Models\PlayerSanction;
use App\Services\PlayerSanctionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlayerSanctionController extends Controller
{
    public function __construct(
        private readonly PlayerSanctionService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', PlayerSanction::class);

        $sanctions = $this->service->all();

        return view('player-sanctions.index', compact('sanctions'));
    }

    public function create(): View
    {
        $this->authorize('create', PlayerSanction::class);

        $players = Player::all();

        return view('player-sanctions.create', compact('players'));
    }

    public function store(CreatePlayerSanctionRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('player-sanctions.index')
            ->with('success', 'Player sanction created successfully.');
    }

    public function edit(PlayerSanction $playerSanction): View
    {
        $this->authorize('update', $playerSanction);

        $players = Player::all();

        return view('player-sanctions.edit', ['sanction' => $playerSanction, 'players' => $players]);
    }

    public function update(UpdatePlayerSanctionRequest $request, PlayerSanction $playerSanction): RedirectResponse
    {
        $this->service->update($playerSanction, $request->validated());

        return redirect()->route('player-sanctions.index')
            ->with('success', 'Player sanction updated successfully.');
    }

    public function destroy(PlayerSanction $playerSanction): RedirectResponse
    {
        $this->authorize('delete', $playerSanction);

        $this->service->delete($playerSanction);

        return redirect()->route('player-sanctions.index')
            ->with('success', 'Player sanction deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $sanction = $this->service->findTrashedById($id);

        $this->authorize('restore', $sanction);

        $this->service->restore($sanction);

        return redirect()->route('player-sanctions.index')
            ->with('success', 'Player sanction restored successfully.');
    }
}
