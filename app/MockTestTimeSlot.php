<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = ['time_range', 'status','slot_key'];
}
