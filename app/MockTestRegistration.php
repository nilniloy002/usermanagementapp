<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestRegistration extends Model
{
    protected $fillable = [
        'mock_test_date_id',
        'name',
        'email',
        'mobile',
        'exam_status_id',
        'no_of_mock_test',
        'mock_test_no',
        'booking_category',
        'exam_type',
        'invoice_no', 
        'lrw_time_slot',
        'speaking_time_slot_id',
        'speaking_room_id',
        'speaking_time_slot_id_another_day',
        'status'
    ];

    public function mockTestDate()
    {
        return $this->belongsTo(MockTestDate::class);
    }

    public function examStatus()
    {
        return $this->belongsTo(MockTestStatus::class, 'exam_status_id');
    }

    public function lrwTimeSlot()
    {
        return $this->belongsTo(MockTestTimeSlot::class, 'lrw_time_slot');
    }

    // Optionally, if you want to define relationships for time slots:
    public function speakingTimeSlot()
    {
        return $this->belongsTo(MockTestTimeSlot::class, 'speaking_time_slot_id');
    }

    public function speakingRoom()
    {
        return $this->belongsTo(MockTestRoom::class, 'speaking_room_id');
    }

}