<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|max:32',
            'description' => 'string|max:255',
            'public' => 'required|boolean',
            'width' => 'required|integer|min:1|max:128',
            'height' => 'required|integer|min:1|max:128',
            'framerate' => 'required|integer|min:1|max:30',
            // TODO: thumbnail and tags architecture
            'thumbnail' => 'string',
        ];
    }
}
