<?php

// app/Http/Controllers/Prices/PriceController.php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Prices\CreatePriceRequest;
use App\Http\Requests\Prices\UpdatePriceRequest;
use App\Models\Price;
use App\Services\PriceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function __construct(
        private readonly PriceService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Price::class);

        $prices = $this->service->all();

        return view('prices.index', compact('prices'));
    }

    public function create(): View
    {
        $this->authorize('create', Price::class);

        return view('prices.create');
    }

    public function store(CreatePriceRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('prices.index')
            ->with('success', 'Price created successfully.');
    }

    public function edit(Price $price): View
    {
        $this->authorize('update', $price);

        return view('prices.edit', compact('price'));
    }

    public function update(UpdatePriceRequest $request, Price $price): RedirectResponse
    {
        $this->service->update($price, $request->validated());

        return redirect()->route('prices.index')
            ->with('success', 'Price updated successfully.');
    }

    public function destroy(Price $price): RedirectResponse
    {
        $this->authorize('delete', $price);

        $this->service->delete($price);

        return redirect()->route('prices.index')
            ->with('success', 'Price deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $price = $this->service->findTrashedById($id);

        $this->authorize('restore', $price);

        $this->service->restore($price);

        return redirect()->route('prices.index')
            ->with('success', 'Price restored successfully.');
    }
}
