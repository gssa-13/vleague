<?php

// app/Http/Requests/IdentityAccess/CreateRoleRequest.php

namespace App\Http\Requests\IdentityAccess;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Role::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:125', 'unique:roles,name'],
        ];
    }
}
