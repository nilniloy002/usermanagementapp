<?php

namespace Vanguard\Http\Requests\MockTestStatus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMockTestStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mock_status' => 'required|string|max:255',
            'status' => 'required|in:On,Off',
        ];
    }
}
