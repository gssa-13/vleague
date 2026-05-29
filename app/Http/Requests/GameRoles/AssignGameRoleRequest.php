<?php

// app/Http/Requests/GameRoles/AssignGameRoleRequest.php

namespace App\Http\Requests\GameRoles;

use App\Models\GameRole;
use Illuminate\Foundation\Http\FormRequest;

class AssignGameRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign', GameRole::class);
    }

    public function rules(): array
    {
        return [
            'game_role_id' => ['required', 'integer', 'exists:game_roles,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
        ];
    }
}
