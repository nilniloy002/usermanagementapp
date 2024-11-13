<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'mobile', 'mocktest_date', 'lstn_cor_ans', 'lstn_score', 'speak_score', 
        'read_cor_ans', 'read_score', 'wrt_task1', 'wrt_task2', 'wrt_score', 'overall_score', 'status'
    ];
}
