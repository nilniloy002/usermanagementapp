<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'batch_code', 
        'course_id', 
        'status',
        'total_seat' // Add this
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get available seats (calculated attribute)
     */
    public function getAvailableSeatsAttribute()
    {
        // You can later calculate this based on enrolled students
        return $this->total_seat;
    }

    /**
     * Check if batch has available seats
     */
    public function getHasAvailableSeatsAttribute()
    {
        return $this->available_seats > 0;
    }
}