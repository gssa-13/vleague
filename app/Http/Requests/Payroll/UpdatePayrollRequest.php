<?php

// app/Http/Requests/Payroll/UpdatePayrollRequest.php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('payroll'));
    }

    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'integer', 'exists:venues,id'],
            'period' => ['required', 'string', 'max:20'],
        ];
    }
}
