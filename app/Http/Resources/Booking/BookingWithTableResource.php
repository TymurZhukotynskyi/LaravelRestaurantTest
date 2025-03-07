<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingWithTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tables' => $this->tables->pluck('id')->toArray(),
            'date' => $this->date,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'guests' => $this->guests,
            'status_id' => $this->status_id,
        ];
    }
}
