<?php

namespace Vanguard\Http\Requests\AnotherDay;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnotherDayRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'speaking_date' => 'required|date',
            'candidate_email' => 'required|email',
            'speaking_time' => 'required|string',
            'zoom_link' => 'required|string',
            'trainers_email' => 'required|email',
        ];
    }
}
