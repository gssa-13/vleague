<?php

// app/Http/Requests/Payroll/CreatePayrollRequest.php

namespace App\Http\Requests\Payroll;

use App\Models\Payroll;
use Illuminate\Foundation\Http\FormRequest;

class CreatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Payroll::class);
    }

    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'integer', 'exists:venues,id'],
            'period' => ['required', 'string', 'max:20'],
        ];
    }
}
