<?php

// app/Http/Requests/Venues/CreateVenueRequest.php

namespace App\Http\Requests\Venues;

use App\Models\Venue;
use Illuminate\Foundation\Http\FormRequest;

class CreateVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Venue::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'max_fields' => ['required', 'integer', 'min:1'],
            'match_duration_minutes' => ['required', 'integer', 'min:1'],
            'advance_booking_days' => ['required', 'integer', 'min:0'],
        ];
    }
}
