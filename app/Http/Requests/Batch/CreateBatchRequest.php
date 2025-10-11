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
            'batch_code' => 'required|string|max:255|unique:batches,batch_code',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:On,Off',
            'total_seat' => 'required|integer|min:1|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'batch_code.required' => 'The batch code field is required.',
            'batch_code.unique' => 'This batch code already exists. Please use a unique batch code.',
            'total_seat.required' => 'The total seats field is required.',
            'total_seat.integer' => 'The total seats must be a number.',
            'total_seat.min' => 'The total seats must be at least 1.',
            'total_seat.max' => 'The total seats may not be greater than 1000.',
        ];
    }
}