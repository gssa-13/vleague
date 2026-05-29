<?php

// app/Services/PayrollService.php

namespace App\Services;

use App\Enums\PayrollAdjustmentType;
use App\Enums\PayrollStatus;
use App\Models\EmployeePayroll;
use App\Models\Payroll;
use App\Models\PayrollAdjustment;
use App\Repositories\PayrollRepository;
use Illuminate\Database\Eloquent\Collection;

class PayrollService
{
    public function __construct(
        private readonly PayrollRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Payroll
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Payroll
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Payroll
    {
        $data['status'] = PayrollStatus::Draft;

        return $this->repository->create($data);
    }

    public function update(Payroll $payroll, array $data): Payroll
    {
        return $this->repository->update($payroll, $data);
    }

    public function delete(Payroll $payroll): void
    {
        $this->repository->delete($payroll);
    }

    public function restore(Payroll $payroll): void
    {
        $this->repository->restore($payroll);
    }

    public function cancel(Payroll $payroll): void
    {
        $this->repository->cancel($payroll);
    }

    public function addAdjustment(int $employeePayrollId, array $data): PayrollAdjustment
    {
        return $this->repository->addAdjustment($employeePayrollId, $data);
    }

    /**
     * Net salary = base salary + additions - deductions.
     */
    public function computeNetSalary(EmployeePayroll $line): float
    {
        $net = (float) $line->base_salary;

        foreach ($line->adjustments as $adjustment) {
            $net += $this->signedAmount($adjustment);
        }

        return $net;
    }

    private function signedAmount(PayrollAdjustment $adjustment): float
    {
        $amount = (float) $adjustment->amount;

        return $adjustment->type === PayrollAdjustmentType::Deduction ? -$amount : $amount;
    }
}
