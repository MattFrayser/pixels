<?php

namespace App\Http\Requests\Projects;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:32',
            'description' => 'sometimes|string|max:255',
            'public' => 'sometimes|boolean',
            'width' => 'sometimes|integer|min:1|max:128',
            'height' => 'sometimes|integer|min:1|max:128',
            'framerate' => 'sometimes|integer|min:1|max:30',
            // TODO: thumbnail and tags architecture
            'thumbnail' => 'sometimes|string',
            'tags' => 'sometimes|string',
        ];
    }
}
