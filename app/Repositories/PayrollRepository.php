<?php

// app/Repositories/PayrollRepository.php

namespace App\Repositories;

use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\PayrollAdjustment;
use Illuminate\Database\Eloquent\Collection;

class PayrollRepository
{
    public function all(): Collection
    {
        return Payroll::with('venue')->get();
    }

    public function findById(int $id): ?Payroll
    {
        return Payroll::find($id);
    }

    public function findTrashedById(int $id): ?Payroll
    {
        return Payroll::withTrashed()->find($id);
    }

    public function create(array $data): Payroll
    {
        return Payroll::create($data);
    }

    public function update(Payroll $payroll, array $data): Payroll
    {
        $payroll->update($data);

        return $payroll;
    }

    public function delete(Payroll $payroll): void
    {
        $payroll->delete();
    }

    public function restore(Payroll $payroll): void
    {
        $payroll->restore();
    }

    public function cancel(Payroll $payroll): void
    {
        $payroll->update(['status' => PayrollStatus::Cancelled]);
    }

    public function addAdjustment(int $employeePayrollId, array $data): PayrollAdjustment
    {
        $data['employee_payroll_id'] = $employeePayrollId;

        return PayrollAdjustment::create($data);
    }
}
