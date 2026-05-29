<?php

// app/Http/Requests/Players/CreatePlayerRequest.php

namespace App\Http\Requests\Players;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Player::class);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
        ];
    }
}
