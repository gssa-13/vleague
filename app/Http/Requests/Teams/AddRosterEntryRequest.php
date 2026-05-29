<?php

// app/Http/Requests/Teams/AddRosterEntryRequest.php

namespace App\Http\Requests\Teams;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;

class AddRosterEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign', Team::class);
    }

    public function rules(): array
    {
        return [
            'player_id' => ['required', 'integer', 'exists:players,id'],
            'jersey_number' => ['nullable', 'integer', 'min:1', 'max:999'],
            'is_captain' => ['nullable', 'boolean'],
            'is_wildcard' => ['nullable', 'boolean'],
        ];
    }
}
