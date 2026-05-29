<?php

// app/Http/Requests/IdentityAccess/AssignRoleRequest.php

namespace App\Http\Requests\IdentityAccess;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assignRole', User::class);
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'string', 'exists:roles,name'],
        ];
    }
}
