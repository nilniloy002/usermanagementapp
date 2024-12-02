<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestRoom extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'mocktest_room',
        'status',
    ];

    // Define the relationship to MockTestRegistration
    public function mockTestRegistrations()
    {
        return $this->hasMany(MockTestRegistration::class, 'speaking_room_id'); // Assuming speaking_room_id is the foreign key
    }

}
