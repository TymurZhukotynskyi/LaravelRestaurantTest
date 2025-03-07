<?php

namespace App\CacheServices\Restaurant;

use App\DTO\Restaurant\RestaurantDTO;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Restaurant\RestaurantRepository;

class RestaurantCacheService
{
    private RestaurantRepository $restaurantRepository;

    public function __construct(RestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function getRestaurantData()
    {
        $data = Cache::remember("RestaurantData", null, function () {
            return (new RestaurantDTO($this->restaurantRepository->getTotalCapacity()))->toArray();
        });

        return RestaurantDTO::fromArray($data);
    }

    public function clearCache(): void
    {
        Cache::forget("TotalRestaurantCapacity");
    }
}
