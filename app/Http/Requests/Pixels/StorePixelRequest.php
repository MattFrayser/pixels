<?php

namespace App\Http\Requests\Pixels;

use Illuminate\Foundation\Http\FormRequest;

class StorePixelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pixels' => 'required|array',
            'pixels.*.x' => 'required|integer|min:0|max:128',
            'pixels.*.y' => 'required|integer|min:0|max:128',
            'pixels.*.color' => 'required|hex_color',
        ];
    }
}
