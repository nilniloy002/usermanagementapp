<?php

namespace Vanguard\Http\Requests\MockTestRegistration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMockTestRegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'mock_test_date_id' => 'required|exists:mock_test_dates,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'exam_status_id' => 'required|exists:mock_test_statuses,id',
            'no_of_mock_test' => 'required|integer|min:1',
            'mock_test_no' => 'required|integer|min:1',
            'invoice_no' => 'nullable|string|max:255',
            'lrw_time_slot' => 'required|string', // Ensure it's a valid time slot string
            'speaking_time_slot_id' => [
                'nullable',
                'exists:mock_test_time_slots,id',
                function ($attribute, $value, $fail) {
                    if ($this->input('speaking_time_slot_id_another_day')) {
                        if ($value) {
                            $fail(__('The speaking time slot cannot be selected if "Another Day" is chosen.'));
                        }
                    }
                },
            ],
            'speaking_time_slot_id_another_day' => 'nullable|boolean',
            'speaking_room_id' => [
                'nullable',
                'exists:mock_test_rooms,id',
                function ($attribute, $value, $fail) {
                    if ($this->input('speaking_time_slot_id_another_day')) {
                        if ($value) {
                            $fail(__('The speaking room cannot be selected if "Another Day" is chosen.'));
                        }
                    } else {
                        if (!$value) {
                            $fail(__('The speaking room is required when "Same Day" is chosen.'));
                        }
                    }
                },
            ],
            'status' => 'required|in:On,Off',
        ];
    }
}
