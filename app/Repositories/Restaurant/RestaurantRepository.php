<?php

namespace App\Repositories\Restaurant;

use Illuminate\Support\Facades\Cache;

class RestaurantRepository
{
    private TableRepository $tableRepository;

    public function __construct(
        TableRepository $tableRepository
    )
    {
        $this->tableRepository = $tableRepository;
    }

    public function getTotalCapacity(): int
    {
        return $this->tableRepository->getAll()->pluck('capacity')->sum();
    }
}
