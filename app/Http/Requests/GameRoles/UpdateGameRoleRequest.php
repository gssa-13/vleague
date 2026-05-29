<?php

// app/Http/Requests/GameRoles/UpdateGameRoleRequest.php

namespace App\Http\Requests\GameRoles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('gameRole'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
