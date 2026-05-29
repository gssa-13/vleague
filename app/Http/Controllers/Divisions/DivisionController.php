<?php

// app/Http/Controllers/Divisions/DivisionController.php

namespace App\Http\Controllers\Divisions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Divisions\CreateDivisionRequest;
use App\Http\Requests\Divisions\UpdateDivisionRequest;
use App\Models\Division;
use App\Models\Tournament;
use App\Services\DivisionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DivisionController extends Controller
{
    public function __construct(
        private readonly DivisionService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Division::class);

        $divisions = $this->service->all();

        return view('divisions.index', compact('divisions'));
    }

    public function create(): View
    {
        $this->authorize('create', Division::class);

        $tournaments = Tournament::all();

        return view('divisions.create', compact('tournaments'));
    }

    public function store(CreateDivisionRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function edit(Division $division): View
    {
        $this->authorize('update', $division);

        $tournaments = Tournament::all();

        return view('divisions.edit', compact('division', 'tournaments'));
    }

    public function update(UpdateDivisionRequest $request, Division $division): RedirectResponse
    {
        $this->service->update($division, $request->validated());

        return redirect()->route('divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        $this->authorize('delete', $division);

        $this->service->delete($division);

        return redirect()->route('divisions.index')
            ->with('success', 'Division deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $division = $this->service->findTrashedById($id);

        $this->authorize('restore', $division);

        $this->service->restore($division);

        return redirect()->route('divisions.index')
            ->with('success', 'Division restored successfully.');
    }
}
