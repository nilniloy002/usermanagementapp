<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestDate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'mocktest_date',
        'exam_pattern',
        'status',
    ];
}
