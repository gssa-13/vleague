<?php

// app/Http/Controllers/Navigation/NavigationItemController.php

namespace App\Http\Controllers\Navigation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Navigation\CreateNavigationItemRequest;
use App\Http\Requests\Navigation\UpdateNavigationItemRequest;
use App\Models\NavigationItem;
use App\Services\NavigationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationItemController extends Controller
{
    public function __construct(
        private readonly NavigationService $service,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', NavigationItem::class);

        $items = $this->service->getVisibleItems($request->user());

        return view('navigation.index', compact('items'));
    }

    public function create(): View
    {
        $this->authorize('create', NavigationItem::class);

        return view('navigation.create');
    }

    public function store(CreateNavigationItemRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('navigation.index')
            ->with('success', 'Navigation item created successfully.');
    }

    public function edit(NavigationItem $navigation): View
    {
        $this->authorize('update', $navigation);

        return view('navigation.edit', ['item' => $navigation]);
    }

    public function update(UpdateNavigationItemRequest $request, NavigationItem $navigation): RedirectResponse
    {
        $this->service->update($navigation, $request->validated());

        return redirect()->route('navigation.index')
            ->with('success', 'Navigation item updated successfully.');
    }

    public function destroy(NavigationItem $navigation): RedirectResponse
    {
        $this->authorize('delete', $navigation);

        $this->service->delete($navigation);

        return redirect()->route('navigation.index')
            ->with('success', 'Navigation item deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = $this->service->findTrashedById($id);

        $this->authorize('restore', $item);

        $this->service->restore($item);

        return redirect()->route('navigation.index')
            ->with('success', 'Navigation item restored successfully.');
    }
}
