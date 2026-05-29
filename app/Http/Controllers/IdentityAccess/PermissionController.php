<?php

// app/Http/Controllers/IdentityAccess/PermissionController.php

namespace App\Http\Controllers\IdentityAccess;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Permission::class);

        $permissions = Permission::all();

        return view('identity-access.permissions.index', compact('permissions'));
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->authorize('delete', $permission);

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $permission = Permission::withTrashed()->findOrFail($id);

        $this->authorize('restore', $permission);

        $permission->restore();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission restored successfully.');
    }
}
