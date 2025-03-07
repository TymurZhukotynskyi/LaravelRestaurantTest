<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $table = 'booking_statuses';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'name'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'status_id');
    }
}
