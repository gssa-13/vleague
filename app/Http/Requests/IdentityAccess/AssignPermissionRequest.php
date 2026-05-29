<?php

// app/Http/Requests/IdentityAccess/AssignPermissionRequest.php

namespace App\Http\Requests\IdentityAccess;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assignPermission', User::class);
    }

    public function rules(): array
    {
        return [
            'permission' => ['required', 'string', 'exists:permissions,name'],
        ];
    }
}
