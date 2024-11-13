<?php

namespace Vanguard\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class CreateBatchRequest extends FormRequest
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
