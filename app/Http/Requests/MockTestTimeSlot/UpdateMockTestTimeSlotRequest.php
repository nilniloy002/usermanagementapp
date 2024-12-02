<?php

namespace Vanguard\Http\Requests\MockTestTimeSlot;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMockTestTimeSlotRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'time_range' => 'required|string|max:255',
            'status' => 'required|in:On,Off',
            'slot_key' => 'required|string|max:255', // Validation for slot_key
        ];
    }
}
