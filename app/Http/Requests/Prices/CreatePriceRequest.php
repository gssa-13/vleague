<?php

// app/Http/Requests/Prices/CreatePriceRequest.php

namespace App\Http\Requests\Prices;

use App\Enums\PriceStatus;
use App\Models\Price;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreatePriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Price::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', new Enum(PriceStatus::class)],
        ];
    }
}
