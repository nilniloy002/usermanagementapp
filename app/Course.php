<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_name',
        'course_fee',
        'admission_fee',
        'status'
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
