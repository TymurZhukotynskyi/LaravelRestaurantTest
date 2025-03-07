<?php

namespace App\Models\Booking;

use App\Models\Restaurant\Table;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'date',
        'time_start',
        'time_end',
        'guests',
        'status_id'
    ];

    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class, 'tables_in_booking')
            ->withTimestamps();
    }
}
