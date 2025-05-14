<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'attendee_id' => 'required|integer|exists:attendees,id'
        ];
    }
}