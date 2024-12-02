<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_status',
        'status',
    ];
}

