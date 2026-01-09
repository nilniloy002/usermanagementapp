<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_admission_id',
        'application_number',
        'student_id',
        'payment_method',
        'transaction_id',
        'serial_number',
        'deposit_amount',
        'discount_amount',
        'due_amount',
        'payment_category',
        'purpose',
        'next_due_date',
        'remarks',
        'payment_received_by'
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'next_due_date' => 'date',
    ];

    /**
     * Boot function to set payment_received_by
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->payment_received_by = Auth::user()->first_name;
            }
        });

        static::updating(function ($model) {
            // Only update payment_received_by if deposit_amount is being changed
            if ($model->isDirty('deposit_amount') && Auth::check()) {
                $model->payment_received_by = Auth::user()->first_name;
            }
        });
    }

    /**
     * Relationships
     */
    public function studentAdmission()
    {
        return $this->belongsTo(StudentAdmission::class);
    }

    /**
     * Accessors
     */
    public function getPaymentMethodNameAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'bkash' => 'bKash',
            'bank' => 'Bank',
            default => ucfirst($this->payment_method)
        };
    }

    public function getTotalPayableAttribute()
    {
        return $this->studentAdmission->course_fee - $this->discount_amount;
    }

    public function getIsPaymentCompletedAttribute()
    {
        return $this->due_amount <= 0;
    }

    /**
     * Get the payment receiver's full name if available
     */
    public function getReceivedByFullNameAttribute()
    {
        if ($this->payment_received_by) {
            // Try to find the user by first name
            $user = \Vanguard\User::where('first_name', $this->payment_received_by)->first();
            if ($user) {
                return $user->first_name . ' ' . $user->last_name;
            }
            return $this->payment_received_by;
        }
        return 'N/A';
    }
}