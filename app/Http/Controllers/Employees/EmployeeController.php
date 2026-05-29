<?php

// app/Http/Controllers/Employees/EmployeeController.php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\CreateEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Employee::class);

        $employees = $this->service->all();

        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        $this->authorize('create', Employee::class);

        return view('employees.create');
    }

    public function store(CreateEmployeeRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee): View
    {
        $this->authorize('update', $employee);

        return view('employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->service->update($employee, $request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->authorize('delete', $employee);

        $this->service->delete($employee);

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $employee = $this->service->findTrashedById($id);

        $this->authorize('restore', $employee);

        $this->service->restore($employee);

        return redirect()->route('employees.index')
            ->with('success', 'Employee restored successfully.');
    }
}
