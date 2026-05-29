<?php

// app/Http/Controllers/Finance/PaymentController.php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\CancelPaymentRequest;
use App\Http\Requests\Finance\CreatePaymentRequest;
use App\Http\Requests\Finance\UpdatePaymentRequest;
use App\Models\Competition;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Payment::class);

        $payments = $this->service->all();

        return view('finance.payments.index', compact('payments'));
    }

    public function create(): View
    {
        $this->authorize('create', Payment::class);

        $competitions = Competition::all();

        return view('finance.payments.create', compact('competitions'));
    }

    public function store(CreatePaymentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Payment created successfully.');
    }

    public function edit(Payment $payment): View
    {
        $this->authorize('update', $payment);

        $competitions = Competition::all();

        return view('finance.payments.edit', compact('payment', 'competitions'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        $this->service->update($payment, $request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $this->authorize('delete', $payment);

        $this->service->delete($payment);

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $payment = $this->service->findTrashedById($id);

        $this->authorize('restore', $payment);

        $this->service->restore($payment);

        return redirect()->route('payments.index')
            ->with('success', 'Payment restored successfully.');
    }

    public function cancel(CancelPaymentRequest $request, Payment $payment): RedirectResponse
    {
        $this->service->cancel($payment, $request->validated('reason'));

        return redirect()->route('payments.index')
            ->with('success', 'Payment cancelled successfully.');
    }
}
