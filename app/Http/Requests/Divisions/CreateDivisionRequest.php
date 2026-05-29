<?php

// app/Http/Requests/Divisions/CreateDivisionRequest.php

namespace App\Http\Requests\Divisions;

use App\Enums\DivisionDay;
use App\Models\Division;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateDivisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Division::class);
    }

    public function rules(): array
    {
        return [
            'tournament_id' => ['required', 'integer', 'exists:tournaments,id'],
            'day' => ['required', new Enum(DivisionDay::class)],
            'field_number' => ['required', 'integer', 'min:1'],
            'group_letter' => ['nullable', 'string', 'size:1', 'alpha'],
        ];
    }
}
