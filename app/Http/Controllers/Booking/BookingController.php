<?php

namespace App\Http\Controllers\Booking;

use App\DTO\Booking\StoreBookingRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\Booking\BookingWithTableCollection;
use App\Models\Booking\Booking;
use App\Repositories\Booking\BookingRepository;
use App\Services\Booking\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    protected BookingRepository $bookingRepository;

    public function __construct(
        BookingService $bookingService,
        BookingRepository $bookingRepository
    )
    {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
    }

    public function createBooking(StoreBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBookingScenario(StoreBookingRequestDTO::fromRequest($request->validated()));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json(new BookingResource($booking), 201);
    }

    public function getBookings(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date parameter is required. In Y-m-d format'], 400);
        }

        try {
            $bookingWithTables = $this->bookingRepository->getBookingsWithTablesByDate($date);
            return response()->json(new BookingWithTableCollection($bookingWithTables), 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Failed to retrieve bookings.'], 500);
        }
    }

    public function cancel(Request $request, int $id)
    {
        try {
            $booking = $this->bookingService->cancelBooking($id);
            return response()->json(new BookingResource($booking), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
