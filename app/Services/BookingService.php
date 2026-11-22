<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    protected CourtAvailabilityService $availabilityService;
    protected PricingService $pricingService;

    public function __construct(
        CourtAvailabilityService $availabilityService,
        PricingService $pricingService
    ) {
        $this->availabilityService = $availabilityService;
        $this->pricingService = $pricingService;
    }

    /**
     * Create a booking with atomic database transactions and concurrency locking.
     */
    public function createBooking(
        User $user,
        Venue $venue,
        string $date,
        array $slots, // [['court_id' => 1, 'start_time' => '18:00:00', 'end_time' => '19:00:00'], ...]
        ?string $notes = null
    ): Booking {
        if (empty($slots)) {
            throw new Exception("Vui lòng chọn ít nhất một khung giờ đặt sân.");
        }

        // Sort slot court IDs to prevent database deadlocks on concurrent bookings
        usort($slots, function ($a, $b) {
            if ($a['court_id'] === $b['court_id']) {
                return strcmp($a['start_time'], $b['start_time']);
            }
            return $a['court_id'] <=> $b['court_id'];
        });

        return DB::transaction(function () use ($user, $venue, $date, $slots, $notes) {
            $totalAmount = 0.00;
            $itemsData = [];

            foreach ($slots as $slot) {
                $courtId = (int)$slot['court_id'];
                $startTime = strlen($slot['start_time']) === 5 ? $slot['start_time'] . ':00' : $slot['start_time'];
                $endTime = strlen($slot['end_time']) === 5 ? $slot['end_time'] . ':00' : $slot['end_time'];

                // 1. Lock court row to prevent race conditions / double bookings
                $court = Court::where('id', $courtId)->lockForUpdate()->firstOrFail();

                // 2. Strict backend availability check
                if (!$this->availabilityService->isSlotAvailable($courtId, $date, $startTime, $endTime)) {
                    throw new Exception("Sân {$court->name} vào khung giờ {$startTime} - {$endTime} không khả dụng hoặc vừa có người đặt.");
                }

                // 3. Calculate price per slot on the server
                $pricing = $this->pricingService->calculateSlotPrice($court, $date, $startTime, $endTime);
                $totalAmount += $pricing['amount'];

                $itemsData[] = [
                    'court_id' => $courtId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_minutes' => $pricing['duration_minutes'],
                    'hourly_rate' => $pricing['hourly_rate'],
                    'amount' => $pricing['amount'],
                ];
            }

            // 4. Generate unique booking code & check-in token
            $bookingCode = 'GS-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $checkInToken = 'GSCK-' . Str::uuid()->toString();

            // 5. Create confirmed booking with unpaid status (Pay at venue)
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $user->id,
                'venue_id' => $venue->id,
                'booking_date' => $date,
                'total_amount' => $totalAmount,
                'discount_amount' => 0.00,
                'final_amount' => $totalAmount,
                'status' => 'confirmed',
                'payment_status' => 'unpaid',
                'payment_method' => 'pay_at_venue',
                'check_in_token' => $checkInToken,
                'notes' => $notes,
            ]);

            // 6. Create booking items
            foreach ($itemsData as $item) {
                $booking->items()->create($item);
            }

            // 7. Record initial status history
            $booking->statusHistories()->create([
                'old_status' => null,
                'new_status' => 'confirmed',
                'changed_by_user_id' => $user->id,
                'reason' => 'Đặt sân thành công - Thanh toán tại sân',
            ]);

            return $booking;
        });
    }

    /**
     * Cancel booking, release time slot, and record status history.
     */
    public function cancelBooking(Booking $booking, User $user, string $reason): array
    {
        if ($booking->status === 'cancelled') {
            return [
                'success' => false,
                'message' => 'Đơn đặt sân này đã được hủy trước đó.',
            ];
        }

        return DB::transaction(function () use ($booking, $user, $reason) {
            $oldStatus = $booking->status;

            $booking->update([
                'status' => 'cancelled',
            ]);

            $booking->statusHistories()->create([
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'changed_by_user_id' => $user->id,
                'reason' => $reason,
            ]);

            return [
                'success' => true,
                'message' => 'Đã hủy đơn đặt sân thành công. Khung giờ đã được giải phóng.',
                'booking' => $booking,
            ];
        });
    }
}
