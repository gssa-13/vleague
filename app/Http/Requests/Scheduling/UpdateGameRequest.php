<?php

// app/Http/Requests/Scheduling/UpdateGameRequest.php

namespace App\Http\Requests\Scheduling;

use App\Enums\GameType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('game'));
    }

    public function rules(): array
    {
        return [
            'competition_id' => ['required', 'integer', 'exists:competitions,id'],
            'home_team_id' => ['nullable', 'integer', 'exists:competition_teams,id'],
            'away_team_id' => ['nullable', 'integer', 'exists:competition_teams,id', 'different:home_team_id'],
            'game_type' => ['required', new Enum(GameType::class)],
            'scheduled_at' => ['required', 'date'],
            'field_number' => ['nullable', 'integer', 'min:1'],
            'matchday' => ['nullable', 'integer', 'min:1'],
            'home_score' => ['nullable', 'integer', 'min:0'],
            'away_score' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
