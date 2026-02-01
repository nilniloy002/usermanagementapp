<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        'educational_background', // Added
        'other_education',        // Added
        'academic_year',          // Added
        'course_id',
        'batch_id',
        'photo_data',
        'application_number',
        'student_id',
        'status',
        'approved_at'
    ];

    protected $casts = [
        'dob' => 'date',
        'approved_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
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

    public function payment()
    {
        return $this->hasOne(StudentPayment::class);
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

    // /**
    //  * Generate unique application number
    //  */
    // public static function generateApplicationNumber()
    // {
    //     $prefix = 'APP';
    //     $year = date('Y');
    //     $month = date('m');
        
    //     do {
    //         $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    //         $applicationNumber = "{$prefix}{$year}{$month}{$random}";
    //     } while (static::where('application_number', $applicationNumber)->exists());

    //     return $applicationNumber;
    // }




        /**
         * Generate unique application number
         */
        public static function generateApplicationNumber()
        {
            $year = date('y');      // 2-digit year
            $month = date('m');     // 2-digit month
            $day = date('d');       // 2-digit day
            $datePrefix = sprintf("%02d%02d%02d", $year, $month, $day); // Format: YYMMDD
            
            // Use database to get the last sequence for today
            $lastAppNumber = static::where('application_number', 'like', $datePrefix . '%')
                ->orderBy('application_number', 'desc')
                ->value('application_number');
            
            if ($lastAppNumber) {
                // Extract the sequence part (last 5 digits)
                $lastSequence = intval(substr($lastAppNumber, -5));
                $nextSequence = $lastSequence + 1;
            } else {
                // First application of the day
                $nextSequence = 1;
            }
            
            // Ensure sequence doesn't exceed 99999
            if ($nextSequence > 99999) {
                // If we exceed 99999, start over with random numbers
                do {
                    $nextSequence = mt_rand(10000, 99999);
                    $appNumber = sprintf("%s%05d", $datePrefix, $nextSequence);
                } while (static::where('application_number', $appNumber)->exists());
            } else {
                $appNumber = sprintf("%s%05d", $datePrefix, $nextSequence);
                
                // Double-check if this number already exists (in case of gaps)
                if (static::where('application_number', $appNumber)->exists()) {
                    // Find the next available number
                    $nextSequence = $nextSequence + 1;
                    while (static::where('application_number', sprintf("%s%05d", $datePrefix, $nextSequence))->exists()) {
                        $nextSequence++;
                        if ($nextSequence > 99999) {
                            // Fallback to random if we reach the limit
                            do {
                                $nextSequence = mt_rand(10000, 99999);
                                $appNumber = sprintf("%s%05d", $datePrefix, $nextSequence);
                            } while (static::where('application_number', $appNumber)->exists());
                            break;
                        }
                    }
                    $appNumber = sprintf("%s%05d", $datePrefix, $nextSequence);
                }
            }
            
            return $appNumber;
        }
    /**
     * Generate unique student ID based on course
     */
    public static function generateStudentId($courseId)
    {
        $course = Course::find($courseId);
        
        if (!$course) {
            throw new \Exception('Course not found for generating student ID');
        }

        $prefix = self::getCoursePrefix($course->course_name);
        $year = date('y'); // Last 2 digits of current year
        
        do {
            $randomAlphabets = chr(rand(65, 90)) . chr(rand(65, 90)); // Two capital letters
            $studentId = "{$prefix}{$year}{$randomAlphabets}";
        } while (static::where('student_id', $studentId)->exists());

        return $studentId;
    }

    /**
     * Get course prefix based on course name
     */
    private static function getCoursePrefix($courseName)
    {
        $prefixes = [
            'IELTS On Computer' => 'SIOC',
            'English Language Foundation Program' => 'SEFP',
            'PTE Preparation Course' => 'SPTE',
            'Junior English Language Program L-1' => 'SJEPL1',
            'Junior English Language Program L-2' => 'SJEPL2',
            'Junior English Language Program L-3' => 'SJEPL3',
            'NextGen Computer Course with AI' => 'SCOM',
            'Beginner\'s Programming for Juniors' => 'SBPJ',
            'English Foundation Course (After SSC)' => 'SESSC',
            'Combo: English Foundation + IELTS' => 'SEFIP',
            'Combo: Computer + Foundation (After SSC)' => 'SBCEF',
        ];

        // Find matching course name (case-insensitive, partial match)
        foreach ($prefixes as $coursePattern => $prefix) {
            if (stripos($courseName, $coursePattern) !== false) {
                return $prefix;
            }
        }

        // Default prefix if no match found
        return 'STU';
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
        return $this->course_fee - ($this->payment->discount_amount ?? 0);
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    public function getPaymentMethodNameAttribute()
    {
        return $this->payment ? $this->payment->payment_method_name : 'N/A';
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_data && Storage::disk('public')->exists($this->photo_data)) {
            return Storage::disk('public')->url($this->photo_data);
        }
        return null;
    }

    // Convenience accessors for payment information
    public function getDepositAmountAttribute()
    {
        return $this->payment ? $this->payment->deposit_amount : 0;
    }

    public function getDiscountAmountAttribute()
    {
        return $this->payment ? $this->payment->discount_amount : 0;
    }

    public function getDueAmountAttribute()
    {
        return $this->payment ? $this->payment->due_amount : 0;
    }

    public function getNextDueDateAttribute()
    {
        return $this->payment ? $this->payment->next_due_date : null;
    }

    public function getTransactionIdAttribute()
    {
        return $this->payment ? $this->payment->transaction_id : null;
    }

    public function getSerialNumberAttribute()
    {
        return $this->payment ? $this->payment->serial_number : null;
    }

    public function getRemarksAttribute()
    {
        return $this->payment ? $this->payment->remarks : null;
    }

    /**
     * Get educational background display text
     */
    public function getEducationalBackgroundTextAttribute()
    {
        $educationOptions = [
            'SSC' => 'SSC',
            'HSC' => 'HSC',
            'bachelor' => 'Bachelor / Honours / Graduation',
            'masters' => 'Master\'s / Post Graduation',
            'others' => 'Others'
        ];

        if ($this->educational_background === 'others' && $this->other_education) {
            return $this->other_education;
        }

        return $educationOptions[$this->educational_background] ?? 'N/A';
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