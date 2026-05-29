<?php

// app/Repositories/EmployeeRepository.php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository
{
    public function all(): Collection
    {
        return Employee::all();
    }

    public function findById(int $id): ?Employee
    {
        return Employee::find($id);
    }

    public function findTrashedById(int $id): ?Employee
    {
        return Employee::withTrashed()->find($id);
    }

    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    public function update(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee;
    }

    public function delete(Employee $employee): void
    {
        $employee->delete();
    }

    public function restore(Employee $employee): void
    {
        $employee->restore();
    }
}
