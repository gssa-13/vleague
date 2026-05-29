<?php

// app/Http/Requests/Scheduling/CancelGameRequest.php

namespace App\Http\Requests\Scheduling;

use Illuminate\Foundation\Http\FormRequest;

class CancelGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cancel', $this->route('game'));
    }

    public function rules(): array
    {
        return [
            'cancellation_reason_id' => ['required', 'integer', 'exists:cancellation_reasons,id'],
        ];
    }
}
