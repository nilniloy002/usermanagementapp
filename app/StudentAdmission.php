<?php
// app/Models/StudentAdmission.php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'course',
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
     * Get course name in readable format
     */
    public function getCourseNameAttribute()
    {
        return match($this->course) {
            'ielts' => 'IELTS',
            'pte' => 'PTE',
            'english_foundation' => 'English Foundation',
            'kids_english' => 'Kids English',
            default => $this->course
        };
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