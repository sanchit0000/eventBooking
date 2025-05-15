<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'title' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'start_time' => 'sometimes|date',
        'end_time' => 'sometimes|date|after:start_time',
        'country' => 'sometimes|string|max:100',
        'capacity' => 'sometimes|integer|min:1'
    ];
}
}