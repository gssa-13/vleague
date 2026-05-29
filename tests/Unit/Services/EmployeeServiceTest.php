<?php

// tests/Unit/Services/EmployeeServiceTest.php

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use App\Services\EmployeeService;

beforeEach(function () {
    $this->repository = Mockery::mock(EmployeeRepository::class);
    $this->service = new EmployeeService($this->repository);
});

it('creates an employee via repository', function () {
    $employee = new Employee;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($employee);

    $result = $this->service->create(['first_name' => 'Juan', 'last_name' => 'Pérez']);

    expect($result)->toBeInstanceOf(Employee::class);
});

it('soft deletes an employee via repository', function () {
    $employee = new Employee;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($employee);

    $this->service->delete($employee);
});

it('restores an employee via repository', function () {
    $employee = new Employee;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($employee);

    $this->service->restore($employee);
});
