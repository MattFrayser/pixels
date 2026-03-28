<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:32',
            'public' => 'required|boolean',
        ];
    }
}
