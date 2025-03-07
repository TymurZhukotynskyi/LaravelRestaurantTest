<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingWithTableCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => BookingWithTableResource::collection($this->collection),
        ];
    }
}
