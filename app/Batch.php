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
        'total_seat',
        'enrolled_students', // Make sure this is included
        'batch_schedule', 

    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentAdmissions()
    {
        return $this->hasMany(StudentAdmission::class);
    }

    /**
     * Get enrolled students count
     */
    public function getEnrolledCountAttribute()
    {
        return $this->studentAdmissions()->where('status', 'approved')->count();
    }

    /**
     * Get available seats (calculated attribute)
     */
    public function getAvailableSeatsAttribute()
    {
        return max(0, $this->total_seat - $this->enrolled_count);
    }

    /**
     * Check if batch has available seats
     */
    public function getHasAvailableSeatsAttribute()
    {
        return $this->available_seats > 0;
    }

    /**
     * Scope for active batches
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'On');
    }
}