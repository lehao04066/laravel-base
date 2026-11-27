<?php

namespace App\Services;

use App\Models\BookingItem;
use App\Models\Court;
use App\Models\OperatingHour;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CourtAvailabilityService
{
    /**
     * Check if a specific time slot is available on a court on a given date.
     */
    public function isSlotAvailable(int $courtId, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        $court = Court::with('venue')->find($courtId);
        if (!$court || !$court->is_active || !$court->venue || !$court->venue->is_active) {
            return false;
        }

        // Standardize time formats (e.g., "08:00" to "08:00:00")
        $startFormatted = strlen($startTime) === 5 ? $startTime . ':00' : $startTime;
        $endFormatted = strlen($endTime) === 5 ? $endTime . ':00' : $endTime;

        // Verify slot is in the future
        $slotStartDatetime = Carbon::parse("$date $startFormatted");
        if ($slotStartDatetime->lte(Carbon::now())) {
            return false;
        }

        // Verify operating hours
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $operatingHour = OperatingHour::where('venue_id', $court->venue_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $venueOpen = $operatingHour ? $operatingHour->open_time : ($court->venue->opening_time ?? '06:00:00');
        $venueClose = $operatingHour ? $operatingHour->close_time : ($court->venue->closing_time ?? '23:00:00');

        if ($operatingHour && $operatingHour->is_closed) {
            return false;
        }

        if ($startFormatted < $venueOpen || $endFormatted > $venueClose) {
            return false;
        }

        // Check conflicting active bookings
        $hasConflict = BookingItem::where('court_id', $courtId)
            ->whereHas('booking', function ($q) use ($date, $excludeBookingId) {
                $q->where(function ($sub) use ($date) {
                    $sub->where('booking_date', $date)
                        ->orWhere('booking_date', 'like', "$date%");
                })->where('status', '!=', 'cancelled');

                if ($excludeBookingId) {
                    $q->where('id', '!=', $excludeBookingId);
                }
            })
            ->where(function ($q) use ($startFormatted, $endFormatted) {
                $q->where('start_time', '<', $endFormatted)
                  ->where('end_time', '>', $startFormatted);
            })
            ->exists();

        return !$hasConflict;
    }

    /**
     * Get 1-hour slots for a court on a date with availability status and pricing.
     */
    public function getCourtSlots(Court $court, string $date, ?PricingService $pricingService = null): Collection
    {
        $pricingService = $pricingService ?? app(PricingService::class);
        $venue = $court->venue;

        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $operatingHour = OperatingHour::where('venue_id', $court->venue_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $openTimeStr = $operatingHour ? $operatingHour->open_time : ($venue->opening_time ?? '06:00:00');
        $closeTimeStr = $operatingHour ? $operatingHour->close_time : ($venue->closing_time ?? '23:00:00');
        $isDayClosed = $operatingHour ? $operatingHour->is_closed : false;

        $openTime = Carbon::parse($openTimeStr);
        $closeTime = Carbon::parse($closeTimeStr);

        // Load active booking items on this court for the specified date
        $bookedItems = BookingItem::where('court_id', $court->id)
            ->whereHas('booking', function ($q) use ($date) {
                $q->where(function ($sub) use ($date) {
                    $sub->where('booking_date', $date)
                        ->orWhere('booking_date', 'like', "$date%");
                })->where('status', '!=', 'cancelled');
            })
            ->get();

        $slots = collect();
        if ($isDayClosed || !$court->is_active || !$venue->is_active) {
            return $slots;
        }

        $now = Carbon::now();
        $current = $openTime->copy();

        while ($current->copy()->addHour()->lte($closeTime)) {
            $slotStart = $current->format('H:i:s');
            $slotEnd = $current->copy()->addHour()->format('H:i:s');
            $displayTime = $current->format('H:i') . ' - ' . $current->copy()->addHour()->format('H:i');

            $slotStartDatetime = Carbon::parse("$date $slotStart");
            $isPast = $slotStartDatetime->lte($now);

            // Check if booked (overlapping intervals)
            $isBooked = $bookedItems->contains(function ($item) use ($slotStart, $slotEnd) {
                return $item->start_time < $slotEnd && $item->end_time > $slotStart;
            });

            $pricing = $pricingService->calculateSlotPrice($court, $date, $slotStart, $slotEnd);
            $price = $pricing['amount'];

            $status = 'available';
            if ($isPast) {
                $status = 'past';
            } elseif ($isBooked) {
                $status = 'booked';
            }

            $slots->push([
                'court_id' => $court->id,
                'court_name' => $court->name,
                'start_time' => $slotStart,
                'end_time' => $slotEnd,
                'display_time' => $displayTime,
                'price' => $price,
                'formatted_price' => number_format($price, 0, ',', '.') . 'đ',
                'is_peak' => $pricing['is_peak'],
                'status' => $status,
                'is_available' => ($status === 'available'),
            ]);

            $current->addHour();
        }

        return $slots;
    }
}
