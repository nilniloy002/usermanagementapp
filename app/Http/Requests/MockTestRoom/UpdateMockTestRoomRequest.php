<?php

namespace Vanguard\Http\Requests\MockTestRoom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMockTestRoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mocktest_room' => 'required|string|max:255',
            'status' => 'required|in:On,Off',
        ];
    }
}
