<?php

// tests/Feature/Employees/EmployeeCrudTest.php

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\User;

it('redirects unauthenticated user away from employees index', function () {
    $this->get(route('employees.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks employees.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('employees.index'))
        ->assertForbidden();
});

it('lists employees when user has employees.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('employees.view');

    Employee::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('employees.index'))
        ->assertOk()
        ->assertViewIs('employees.index');
});

it('creates an employee when actor has employees.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('employees.create');

    $this->actingAs($actor)
        ->post(route('employees.store'), [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'phone' => '5551234567',
            'hire_date' => '2024-01-15',
        ])
        ->assertRedirect(route('employees.index'));

    $this->assertDatabaseHas('employees', ['first_name' => 'Juan', 'last_name' => 'Pérez']);
});

it('fails validation when creating employee with missing required fields', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('employees.create');

    $this->actingAs($actor)
        ->post(route('employees.store'), [])
        ->assertSessionHasErrors(['first_name', 'last_name', 'hire_date']);
});

it('updates an employee when actor has employees.update permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('employees.update');

    $employee = Employee::factory()->create(['first_name' => 'Old']);

    $this->actingAs($actor)
        ->put(route('employees.update', $employee), [
            'first_name' => 'New',
            'last_name' => $employee->last_name,
            'hire_date' => $employee->hire_date->toDateString(),
        ])
        ->assertRedirect(route('employees.index'));

    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'first_name' => 'New']);
});

it('soft deletes an employee when actor has employees.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('employees.delete');

    $employee = Employee::factory()->create();

    $this->actingAs($actor)
        ->delete(route('employees.destroy', $employee))
        ->assertRedirect(route('employees.index'));

    $this->assertSoftDeleted('employees', ['id' => $employee->id]);
});

it('excludes soft-deleted employees from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('employees.view');

    $employee = Employee::factory()->create();
    $employee->delete();

    $response = $this->actingAs($viewer)->get(route('employees.index'));

    $employees = $response->viewData('employees');
    expect($employees->contains('id', $employee->id))->toBeFalse();
});

it('restores a soft-deleted employee when actor has employees.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('employees.restore');

    $employee = Employee::factory()->create();
    $employee->delete();

    $this->actingAs($actor)
        ->post(route('employees.restore', $employee->id))
        ->assertRedirect(route('employees.index'));

    $this->assertNotSoftDeleted('employees', ['id' => $employee->id]);
});

it('records an activity log entry when an employee is created', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('employees.create');

    $this->actingAs($actor)
        ->post(route('employees.store'), [
            'first_name' => 'Logged',
            'last_name' => 'Employee',
            'hire_date' => '2024-01-01',
        ]);

    $employee = Employee::where('first_name', 'Logged')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Employee::class)
            ->where('record_id', $employee->id)
            ->exists()
    )->toBeTrue();
});
