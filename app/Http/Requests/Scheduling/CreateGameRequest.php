<?php

// app/Http/Requests/Scheduling/CreateGameRequest.php

namespace App\Http\Requests\Scheduling;

use App\Enums\GameType;
use App\Models\Game;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Game::class);
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
        ];
    }
}
