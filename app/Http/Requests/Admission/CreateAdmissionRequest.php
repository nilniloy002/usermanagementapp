<?php

namespace Vanguard\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdmissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
           //'name' => 'required|string|max:255',
        ];
    }

}
