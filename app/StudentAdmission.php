<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

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
        'course_id',
        'batch_id',
        'photo_data',
        'payment_method',
        'transaction_id',
        'serial_number',
        'deposit_amount',
        'discount_amount',
        'due_amount',
        'next_due_date',
        'remarks',
        'application_number',
        'student_id',
        'status',
        'approved_at'
    ];

    protected $casts = [
        'dob' => 'date',
        'next_due_date' => 'date',
        'approved_at' => 'datetime',
        'deposit_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'pending',
        'deposit_amount' => 0,
        'discount_amount' => 0,
        'due_amount' => 0,
    ];

    /**
     * Relationships
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
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
     * Generate unique student ID
     */
    public static function generateStudentId()
    {
        $prefix = 'STU';
        $year = date('y');
        
        do {
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $studentId = "{$prefix}{$year}{$random}";
        } while (static::where('student_id', $studentId)->exists());

        return $studentId;
    }

    /**
     * Accessors
     */
    public function getCourseNameAttribute()
    {
        return $this->course ? $this->course->course_name : 'N/A';
    }

    public function getCourseFeeAttribute()
    {
        return $this->course ? $this->course->course_fee : 0;
    }

    public function getBatchCodeAttribute()
    {
        return $this->batch ? $this->batch->batch_code : 'N/A';
    }

    public function getTotalPayableAttribute()
    {
        return $this->course_fee - $this->discount_amount;
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    public function getPaymentMethodNameAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'bkash' => 'bKash',
            'bank' => 'Bank',
            default => ucfirst($this->payment_method)
        };
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_data && Storage::disk('public')->exists($this->photo_data)) {
            return Storage::disk('public')->url($this->photo_data);
        }
        return null;
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if payment is completed
     */
    public function getIsPaymentCompletedAttribute()
    {
        return $this->due_amount <= 0;
    }
}