<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'bill_id',
        'paid_amount',
        'discount_amount',
        'payment_date',
        'payment_process',
        'payment_method',
        'next_due_date',
        'remarks',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'bill_id', 'bill_id');
    }
}
