<?php

// app/Services/EmployeeService.php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Database\Eloquent\Collection;

class EmployeeService
{
    public function __construct(
        private readonly EmployeeRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Employee
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Employee
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Employee
    {
        return $this->repository->create($data);
    }

    public function update(Employee $employee, array $data): Employee
    {
        return $this->repository->update($employee, $data);
    }

    public function delete(Employee $employee): void
    {
        $this->repository->delete($employee);
    }

    public function restore(Employee $employee): void
    {
        $this->repository->restore($employee);
    }
}
