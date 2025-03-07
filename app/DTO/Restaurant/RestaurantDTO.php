<?php

namespace App\DTO\Restaurant;

class RestaurantDTO
{
    public function __construct(
        public readonly int $totalCapacity
    ) {
    }

    public function toArray(): array
    {
        return [
            'totalCapacity' => $this->totalCapacity,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['totalCapacity'],
        );
    }

    public static function fill(int $totalCapacity): self
    {
        return new self(
            totalCapacity: $totalCapacity
        );
    }
}
