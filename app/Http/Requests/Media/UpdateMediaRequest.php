<?php

// app/Http/Requests/Media/UpdateMediaRequest.php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('media'));
    }

    public function rules(): array
    {
        return [
            'media_type_id' => ['required', 'integer', 'exists:media_types,id'],
            'name' => ['required', 'string', 'max:100'],
            'file' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
