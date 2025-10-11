<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Course;

class StudentAdmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dob',
        'gender',
        'mobile',
        'emergency_mobile',
        'email',
        'address',
        'course_id', // Add course_id
        'photo_data',
        'payment_method',
        'transaction_id',
        'serial_number',
        'application_number',
        'status'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Relationship with Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Boot function to generate application number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->application_number = static::generateApplicationNumber();
        });
    }

    /**
     * Generate unique application number
     */
    public static function generateApplicationNumber()
    {
        $prefix = 'APP';
        $year = date('Y');
        $month = date('m');
        
        do {
            $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $applicationNumber = "{$prefix}{$year}{$month}{$random}";
        } while (static::where('application_number', $applicationNumber)->exists());

        return $applicationNumber;
    }

    /**
     * Accessor for course name
     */
    public function getCourseNameAttribute()
    {
        return $this->course ? $this->course->course_name : 'N/A';
    }

    /**
     * Accessor for course fee
     */
    public function getCourseFeeAttribute()
    {
        return $this->course ? $this->course->course_fee : 0;
    }

    /**
     * Scope for pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved applications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get payment method in readable format
     */
    public function getPaymentMethodNameAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'bkash' => 'bKash',
            'bank' => 'Bank',
            default => $this->payment_method
        };
    }
}