<?php

namespace Vanguard\Http\Requests\MockTestDate;

use Illuminate\Foundation\Http\FormRequest;

class CreateMockTestDateRequest extends FormRequest
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
            'exam_pattern' => ['required', 'in:IoP,IoC,PTE'], // exam_pattern must be 'IoP'or 'IoC'
        ];
    }
}
