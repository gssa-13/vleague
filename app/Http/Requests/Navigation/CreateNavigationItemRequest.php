<?php

// app/Http/Requests/Navigation/CreateNavigationItemRequest.php

namespace App\Http\Requests\Navigation;

use App\Models\NavigationItem;
use Illuminate\Foundation\Http\FormRequest;

class CreateNavigationItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', NavigationItem::class);
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
