<?php

namespace App\Models\Restaurant;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'capacity'
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'tables_in_booking')
            ->withTimestamps();
    }
}
