<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    // use HasFactory;
    protected $table = 'admissions';
    protected $fillable = ['bill_id','admission_date','student_name','phone_number','guardian_phone_number','course_id','batch_code','discount_amount','paid_amount','payment_method','payment_process','due_amount','photo'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'bill_id', 'bill_id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_code');
    }
}
