<?php

// app/Http/Controllers/Teams/TeamRosterController.php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\AddRosterEntryRequest;
use App\Models\CompetitionTeam;
use App\Models\Team;
use App\Models\TeamRoster;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;

class TeamRosterController extends Controller
{
    public function __construct(
        private readonly TeamService $service,
    ) {}

    public function store(AddRosterEntryRequest $request, CompetitionTeam $competitionTeam): RedirectResponse
    {
        try {
            $this->service->addRosterEntry($competitionTeam->id, $request->validated());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['is_captain' => $e->getMessage()])->withInput();
        }

        return redirect()->back()
            ->with('success', 'Player added to roster successfully.');
    }

    public function destroy(CompetitionTeam $competitionTeam, TeamRoster $roster): RedirectResponse
    {
        $this->authorize('assign', Team::class);

        $this->service->removeRosterEntry($roster);

        return redirect()->back()
            ->with('success', 'Player removed from roster successfully.');
    }
}
