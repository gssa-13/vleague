<?php

// app/Http/Requests/Media/CreateMediaRequest.php

namespace App\Http\Requests\Media;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;

class CreateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Media::class);
    }

    public function rules(): array
    {
        return [
            'media_type_id' => ['required', 'integer', 'exists:media_types,id'],
            'name' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'max:10240'],
        ];
    }
}
