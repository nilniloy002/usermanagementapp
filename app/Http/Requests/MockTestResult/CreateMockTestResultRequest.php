<?php

namespace Vanguard\Http\Requests\MockTestResult;

use Illuminate\Foundation\Http\FormRequest;

class CreateMockTestResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'mocktest_date' => 'required|date',
            'lstn_cor_ans' => 'required',
            'lstn_score' => 'required',
            'speak_score' => 'required',
            'read_cor_ans' => 'required',
            'read_score' => 'required',
            'wrt_task1' => 'required',
            'wrt_task2' => 'required',
            'wrt_score' => 'required',
            'overall_score' => 'required',
            'status' => 'required|in:On,Off',
        ];
    }
}
