<?php

// app/Http/Requests/Finance/CreatePaymentRequest.php

namespace App\Http\Requests\Finance;

use App\Enums\PaymentConcept;
use App\Enums\PaymentMethod;
use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Payment::class);
    }

    public function rules(): array
    {
        return [
            'competition_id' => ['nullable', 'integer', 'exists:competitions,id'],
            'competition_team_id' => ['nullable', 'integer', 'exists:competition_teams,id'],
            'concept' => ['required', new Enum(PaymentConcept::class)],
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0', 'lte:amount'],
        ];
    }
}
