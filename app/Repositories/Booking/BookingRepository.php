<?php

namespace App\Repositories\Booking;

use App\DTO\Booking\StoreBookingRequestDTO;
use App\Models\Booking\Booking;
use App\Models\Booking\TableInBooking;
use App\Models\Restaurant\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository
{

    public function getFreeTablesForTime(string $date, string $timeStart, string $timeEnd): Collection
    {
        $reservedTableIds = $this->getReservedTablesForTime($date, $timeStart, $timeEnd)
            ->pluck('table_id')
            ->toArray();

        return Table::query()
            ->whereNotIn('id', $reservedTableIds)
            ->get();
    }

    public function getReservedTablesForTime(string $date, string $timeStart, string $timeEnd)
    {
         return DB::table('tables_in_booking')
            ->join('bookings', 'tables_in_booking.booking_id', '=', 'bookings.id')
            ->where('bookings.date', $date)
            ->where('bookings.status_id', 1)
            ->where('bookings.time_start', '<', $timeEnd)
            ->where('bookings.time_end', '>', $timeStart)
            ->get();
    }

    public function createBooking(StoreBookingRequestDTO $storeBookingRequestDTO): Booking
    {
        $booking = new Booking([
            'date' => $storeBookingRequestDTO->date,
            'time_start' => $storeBookingRequestDTO->timeStart,
            'time_end' => $storeBookingRequestDTO->timeEnd,
            'guests' => $storeBookingRequestDTO->guests,
            'status_id' => 1
        ]);

        $booking->save();
        return $booking;
    }

    public function reserveTablesForBooking(Booking $booking, Collection $reservedTables): Collection
    {
        $confirmedTableReservations = new Collection();
        foreach ($reservedTables as $reservedTable) {
            $tableInBooking = new TableInBooking([
                'booking_id' => $booking->id,
                'table_id' => $reservedTable->id
            ]);

            if ($tableInBooking->save()) {
                $confirmedTableReservations[] = $tableInBooking;
            } else {
                throw new \Exception('Smth goes wrong! Can not reserve a table.');
            }
        }

        return $confirmedTableReservations;
    }

    public function getBookingsWithTablesByDate(string $date): Collection
    {
        return Booking::where('date', $date)
            ->with(['tables' => function ($query) {
                $query->select('tables.id');
            }])
            ->orderBy('time_start', 'asc')
            ->get();
    }

    public function cancelBooking(int $bookingId): ?Booking
    {
        $booking = Booking::find($bookingId);

        if ($booking) {
            $booking->status_id = 2; // Скасовано
            $booking->save();
            return $booking;
        }

        return null;
    }

}
