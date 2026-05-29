<?php

// app/Http/Requests/GameRoles/CreateGameRoleRequest.php

namespace App\Http\Requests\GameRoles;

use App\Models\GameRole;
use Illuminate\Foundation\Http\FormRequest;

class CreateGameRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', GameRole::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
