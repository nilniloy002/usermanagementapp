<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $fillable = ['batch_code', 'course_id', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
