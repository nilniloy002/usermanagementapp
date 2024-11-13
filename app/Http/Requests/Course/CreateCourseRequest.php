<?php

namespace Vanguard\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
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
