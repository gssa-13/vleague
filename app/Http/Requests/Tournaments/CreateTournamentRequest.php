<?php

// app/Http/Requests/Tournaments/CreateTournamentRequest.php

namespace App\Http\Requests\Tournaments;

use App\Enums\TournamentStatus;
use App\Models\Tournament;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateTournamentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tournament::class);
    }

    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'integer', 'exists:venues,id'],
            'name' => ['required', 'string', 'max:100'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', new Enum(TournamentStatus::class)],
        ];
    }
}
