<?php

namespace Vanguard\Http\Requests\MockTestDate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'mocktest_date' => [
                'required',
                'date',
                Rule::unique('mock_test_dates', 'mocktest_date')->ignore($this->route('mockTestDate')?->id),
            ],
            'status' => ['required', 'in:On,Off'], // Status must be 'On' or 'Off'
            'exam_pattern' => ['required', 'in:IoP,IoC,PTE'], // exam_pattern must be 'IoP'or 'IoC'

        ];
    }
}
