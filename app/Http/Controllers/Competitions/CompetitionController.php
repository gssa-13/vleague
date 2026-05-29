<?php

// app/Http/Controllers/Competitions/CompetitionController.php

namespace App\Http\Controllers\Competitions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Competitions\CreateCompetitionRequest;
use App\Models\Competition;
use App\Models\Division;
use App\Models\Tournament;
use App\Models\Venue;
use App\Services\CompetitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function __construct(
        private readonly CompetitionService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Competition::class);

        $competitions = $this->service->all();

        return view('competitions.index', compact('competitions'));
    }

    public function create(): View
    {
        $this->authorize('create', Competition::class);

        $venues = Venue::all();
        $tournaments = Tournament::all();
        $divisions = Division::all();

        return view('competitions.create', compact('venues', 'tournaments', 'divisions'));
    }

    public function store(CreateCompetitionRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['combination' => $e->getMessage()])->withInput();
        }

        return redirect()->route('competitions.index')
            ->with('success', 'Competition created successfully.');
    }

    public function destroy(Competition $competition): RedirectResponse
    {
        $this->authorize('delete', $competition);

        $this->service->delete($competition);

        return redirect()->route('competitions.index')
            ->with('success', 'Competition deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $competition = $this->service->findTrashedById($id);

        $this->authorize('restore', $competition);

        $this->service->restore($competition);

        return redirect()->route('competitions.index')
            ->with('success', 'Competition restored successfully.');
    }
}
