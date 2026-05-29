<?php

// app/Http/Controllers/Payroll/PayrollController.php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\CreatePayrollRequest;
use App\Http\Requests\Payroll\UpdatePayrollRequest;
use App\Models\Payroll;
use App\Models\Venue;
use App\Services\PayrollService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function __construct(
        private readonly PayrollService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Payroll::class);

        $payrolls = $this->service->all();

        return view('payroll.index', compact('payrolls'));
    }

    public function create(): View
    {
        $this->authorize('create', Payroll::class);

        $venues = Venue::all();

        return view('payroll.create', compact('venues'));
    }

    public function store(CreatePayrollRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll created successfully.');
    }

    public function edit(Payroll $payroll): View
    {
        $this->authorize('update', $payroll);

        $venues = Venue::all();

        return view('payroll.edit', compact('payroll', 'venues'));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll): RedirectResponse
    {
        $this->service->update($payroll, $request->validated());

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        $this->authorize('delete', $payroll);

        $this->service->delete($payroll);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $payroll = $this->service->findTrashedById($id);

        $this->authorize('restore', $payroll);

        $this->service->restore($payroll);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll restored successfully.');
    }

    public function cancel(Payroll $payroll): RedirectResponse
    {
        $this->authorize('cancel', $payroll);

        $this->service->cancel($payroll);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll cancelled successfully.');
    }
}
