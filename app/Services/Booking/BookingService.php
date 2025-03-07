<?php

namespace App\Services\Booking;

use App\CacheServices\Restaurant\RestaurantCacheService;
use App\DTO\Booking\StoreBookingRequestDTO;
use App\Models\Booking\Booking;
use App\Repositories\Booking\BookingRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


class BookingService
{
    private RestaurantCacheService $restaurantCacheService;

    private BookingRepository $bookingRepository;

    public function __construct(
        RestaurantCacheService $restaurantCacheService,
        BookingRepository $bookingRepository
    )
    {
        $this->restaurantCacheService = $restaurantCacheService;
        $this->bookingRepository = $bookingRepository;
    }

    public function createBookingScenario(StoreBookingRequestDTO $storeBookingRequestDTO): Booking
    {
        return DB::transaction(function () use ($storeBookingRequestDTO) {
            $guestsCanVisitRestaurant = $this->compareCapacityOfRestaurantToGuests($storeBookingRequestDTO->guests);
            if (!$guestsCanVisitRestaurant) {
                throw new \Exception('Number of guests exceeds restaurant capacity.');
            }

            $freeTables = $this->bookingRepository->getFreeTablesForTime(
                $storeBookingRequestDTO->date,
                $storeBookingRequestDTO->timeStart,
                $storeBookingRequestDTO->timeEnd
            );

            $guestsCanVisitRestaurantInThisTime = $this->compareCapacityOfFreeTablesToGuests(
                $freeTables,
                $storeBookingRequestDTO->guests
            );
            if (!$guestsCanVisitRestaurantInThisTime) {
                throw new \Exception('Not enough free tables available for the requested time.');
            }

            $reservedTables = $this->reserveFreeTablesWithGuests(
                $freeTables,
                $storeBookingRequestDTO->guests
            );
            if ($reservedTables->isEmpty()) {
                throw new \Exception('No tables could be reserved for the requested number of guests.');
            }

            $booking = $this->bookingRepository->createBooking($storeBookingRequestDTO);

            $confirmedTableReservations = $this->bookingRepository->reserveTablesForBooking($booking, $reservedTables);
            if ($confirmedTableReservations->isEmpty()) {
                throw new \Exception('No tables could be reserved for the requested number of guests.');
            }
            return $booking;
        });
    }

    private function compareCapacityOfRestaurantToGuests(int $guests): bool
    {
        $restaurantData = $this->restaurantCacheService->getRestaurantData();

        return $restaurantData->totalCapacity > $guests;
    }

    private function compareCapacityOfFreeTablesToGuests(Collection $freeTables, int $guests): bool
    {
        return $freeTables->pluck('capacity')->sum() > $guests;
    }

    private function reserveFreeTablesWithGuests(Collection $freeTables, int $guests): Collection
    {
        $freeTables = $freeTables->sortByDesc('capacity');

        $reservedTables = new Collection();

        foreach ($freeTables as $freeTable) {
            $reservedTables[] = $freeTable;
            $guests -= $freeTable->capacity;

            if ($guests < 1) {
                break;
            }
        }

        return $reservedTables;
    }

    public function cancelBooking(int $bookingId): Booking
    {
        $booking = $this->bookingRepository->cancelBooking($bookingId);

        if (!$booking) {
            throw new \Exception('Booking not found.');
        }

        $startTime = Carbon::parse("{$booking->date} {$booking->time_start}");
        $now = Carbon::now();
        $hoursBeforeStart = $now->diffInHours($startTime, false);

        if ($hoursBeforeStart < 2) {
            throw new \Exception('Booking can only be cancelled at least 2 hours before the start time.');
        }

        return $booking;
    }
}
