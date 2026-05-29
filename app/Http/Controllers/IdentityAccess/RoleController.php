<?php

// app/Http/Controllers/IdentityAccess/RoleController.php

namespace App\Http\Controllers\IdentityAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdentityAccess\CreateRoleRequest;
use App\Http\Requests\IdentityAccess\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Role::class);

        $roles = $this->service->all();

        return view('identity-access.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('create', Role::class);

        return view('identity-access.roles.create');
    }

    public function store(CreateRoleRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $this->authorize('update', $role);

        return view('identity-access.roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->service->update($role, $request->validated());

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        $this->service->delete($role);

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $role = $this->service->findTrashedById($id);

        $this->authorize('restore', $role);

        $this->service->restore($role);

        return redirect()->route('roles.index')
            ->with('success', 'Role restored successfully.');
    }
}
