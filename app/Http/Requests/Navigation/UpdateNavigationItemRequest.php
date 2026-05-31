<?php

// app/Http/Requests/Navigation/UpdateNavigationItemRequest.php

namespace App\Http\Requests\Navigation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNavigationItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('navigation'));
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:100'],
            'label_key' => ['nullable', 'string', 'max:150'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
            'permission_name' => ['nullable', 'string', 'max:100'],
            'parent_id' => ['nullable', 'integer', 'exists:navigation_items,id'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
