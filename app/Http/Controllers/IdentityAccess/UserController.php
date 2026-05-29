<?php

// app/Http/Controllers/IdentityAccess/UserController.php

namespace App\Http\Controllers\IdentityAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdentityAccess\AssignPermissionRequest;
use App\Http\Requests\IdentityAccess\AssignRoleRequest;
use App\Http\Requests\IdentityAccess\CreateUserRequest;
use App\Http\Requests\IdentityAccess\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = $this->service->all();

        return view('identity-access.users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('identity-access.users.create');
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('identity-access.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->service->update($user, $request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $this->service->delete($user);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $user = $this->service->findTrashedById($id);

        $this->authorize('restore', $user);

        $this->service->restore($user);

        return redirect()->route('users.index')
            ->with('success', 'User restored successfully.');
    }

    public function assignRole(AssignRoleRequest $request, User $user): RedirectResponse
    {
        $this->service->assignRole($user, $request->validated('role'));

        return redirect()->back()
            ->with('success', 'Role assigned successfully.');
    }

    public function revokeRole(User $user, string $role): RedirectResponse
    {
        $this->authorize('assignRole', User::class);

        $this->service->revokeRole($user, $role);

        return redirect()->back()
            ->with('success', 'Role revoked successfully.');
    }

    public function assignPermission(AssignPermissionRequest $request, User $user): RedirectResponse
    {
        $this->service->assignPermission($user, $request->validated('permission'));

        return redirect()->back()
            ->with('success', 'Permission assigned successfully.');
    }
}
