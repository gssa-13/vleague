<?php

// app/Http/Requests/Competitions/CreateCompetitionRequest.php

namespace App\Http\Requests\Competitions;

use App\Models\Competition;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompetitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Competition::class);
    }

    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'integer', 'exists:venues,id'],
            'tournament_id' => ['required', 'integer', 'exists:tournaments,id'],
            'division_id' => ['required', 'integer', 'exists:divisions,id'],
            'price_id' => ['nullable', 'integer'],
        ];
    }
}
