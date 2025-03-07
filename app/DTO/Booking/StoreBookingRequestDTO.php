<?php

namespace App\DTO\Booking;

class StoreBookingRequestDTO
{
    public function __construct(
        public readonly string $date,
        public readonly string $timeStart,
        public readonly string $timeEnd,
        public readonly int $guests
    ) {
    }

// Метод для створення DTO із валідованих даних запиту
    public static function fromRequest(array $validatedData): self
    {
    return new self(
            date: $validatedData['date'],
            timeStart: $validatedData['time_start'],
            timeEnd: $validatedData['time_end'],
            guests: $validatedData['guests']
        );
    }
}
