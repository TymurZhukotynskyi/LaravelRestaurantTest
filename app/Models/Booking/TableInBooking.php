<?php

namespace App\Models\Booking;

use App\Models\Restaurant\Table;
use Illuminate\Database\Eloquent\Model;

class TableInBooking extends Model
{
    protected $table = 'tables_in_booking';
    protected $fillable = [
        'booking_id',
        'table_id'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
