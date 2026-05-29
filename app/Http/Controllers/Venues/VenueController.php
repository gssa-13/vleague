<?php

// app/Http/Controllers/Venues/VenueController.php

namespace App\Http\Controllers\Venues;

use App\Http\Controllers\Controller;
use App\Http\Requests\Venues\CreateVenueRequest;
use App\Http\Requests\Venues\UpdateVenueRequest;
use App\Models\Venue;
use App\Services\VenueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function __construct(
        private readonly VenueService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Venue::class);

        $venues = $this->service->all();

        return view('venues.index', compact('venues'));
    }

    public function create(): View
    {
        $this->authorize('create', Venue::class);

        return view('venues.create');
    }

    public function store(CreateVenueRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('venues.index')
            ->with('success', 'Venue created successfully.');
    }

    public function edit(Venue $venue): View
    {
        $this->authorize('update', $venue);

        return view('venues.edit', compact('venue'));
    }

    public function update(UpdateVenueRequest $request, Venue $venue): RedirectResponse
    {
        $this->service->update($venue, $request->validated());

        return redirect()->route('venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        $this->authorize('delete', $venue);

        $this->service->delete($venue);

        return redirect()->route('venues.index')
            ->with('success', 'Venue deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $venue = $this->service->findTrashedById($id);

        $this->authorize('restore', $venue);

        $this->service->restore($venue);

        return redirect()->route('venues.index')
            ->with('success', 'Venue restored successfully.');
    }
}
