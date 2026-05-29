<?php

// app/Http/Requests/PlayerSanctions/CreatePlayerSanctionRequest.php

namespace App\Http\Requests\PlayerSanctions;

use App\Models\PlayerSanction;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerSanctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', PlayerSanction::class);
    }

    public function rules(): array
    {
        return [
            'player_id' => ['required', 'integer', 'exists:players,id'],
            'competition_team_id' => ['nullable', 'integer', 'exists:competition_teams,id'],
            'competition_id' => ['nullable', 'integer', 'exists:competitions,id'],
            'reason' => ['required', 'string', 'max:255'],
            'sanctioned_games' => ['required', 'integer', 'min:1'],
            'served_games' => ['nullable', 'integer', 'min:0', 'lte:sanctioned_games'],
        ];
    }
}
