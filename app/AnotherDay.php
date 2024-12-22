<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnotherDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'speaking_date',
        'candidate_email',
        'speaking_time',
        'zoom_link',
        'trainers_email',
        'status'
    ];
}
