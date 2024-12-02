<?php

namespace Vanguard\Http\Requests\MockTestDate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMockTestDateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You can add authorization logic here
        return true;
    }

    public function rules(): array
    {
        return [
            'mocktest_date' => ['required', 'date'],
            'status' => ['required', 'in:On,Off'], // Status must be 'On' or 'Off'
        ];
    }
}
