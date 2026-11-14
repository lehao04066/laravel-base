<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['sometimes', 'date_format:Y-m-d'],
        ]);
        $date = CarbonImmutable::parse($validated['date'] ?? today()->toDateString());
        $today = CarbonImmutable::today();
        $now = CarbonImmutable::now();

        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $venueId = DB::table('staff_profiles')
            ->where('user_id', $user->id)
            ->value('venue_id');

        if (! $venueId) {
            $venueId = DB::table('role_user')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $user->id)
                ->where('roles.name', 'staff')
                ->value('role_user.venue_id');
        }

        abort_unless($venueId, 403, 'Tài khoản lễ tân chưa được gán cơ sở.');

        $venue = DB::table('venues')
            ->where('id', $venueId)
            ->whereNull('deleted_at')
            ->first();

        abort_unless($venue, 404, 'Không tìm thấy cơ sở được gán cho tài khoản.');

        $courts = DB::table('courts as c')
            ->leftJoin('court_types as ct', 'ct.id', '=', 'c.court_type_id')
            ->where('c.venue_id', $venueId)
            ->whereNull('c.deleted_at')
            ->orderBy('c.court_number')
            ->get([
                'c.id',
                'c.court_number',
                'c.name',
                'c.surface_type',
                'c.is_active',
                'ct.name as court_type_name',
            ]);

        $courtIds = $courts->pluck('id');
        $dateString = $date->toDateString();
        $dayStart = $date->startOfDay()->toDateTimeString();
        $dayEnd = $date->addDay()->startOfDay()->toDateTimeString();

        $bookingsByCourt = DB::table('booking_items as bi')
            ->join('bookings as b', 'b.id', '=', 'bi.booking_id')
            ->leftJoin('users as u', 'u.id', '=', 'b.user_id')
            ->where('b.venue_id', $venueId)
            ->whereIn('bi.court_id', $courtIds)
            ->where('b.booking_date', $dateString)
            ->whereNull('b.deleted_at')
            ->whereNotIn('b.status', ['cancelled', 'canceled', 'expired', 'rejected'])
            ->orderBy('bi.start_time')
            ->get([
                'bi.court_id',
                'bi.booking_id',
                'bi.start_time',
                'bi.end_time',
                'b.booking_code',
                'b.status as booking_status',
                'b.payment_status',
                'u.name as customer_name',
            ])
            ->groupBy('court_id');

        $activeCheckIns = DB::table('check_ins')
            ->whereIn('court_id', $courtIds)
            ->whereDate('check_in_time', $dateString)
            ->whereNull('check_out_time')
            ->get(['booking_id', 'court_id'])
            ->mapWithKeys(fn ($checkIn) => [$checkIn->court_id.':'.$checkIn->booking_id => true]);

        $maintenanceByCourt = DB::table('court_maintenance')
            ->whereIn('court_id', $courtIds)
            ->where('start_datetime', '<', $dayEnd)
            ->where('end_datetime', '>', $dayStart)
            ->whereNotIn('status', ['cancelled', 'canceled', 'completed'])
            ->get(['court_id', 'title', 'reason'])
            ->groupBy('court_id');

        $counts = [
            'all' => $courts->count(),
            'playing' => 0,
            'upcoming' => 0,
            'available' => 0,
            'maintenance' => 0,
            'completed' => 0,
        ];
        $courtCards = $courts->map(function ($court) use (
            $activeCheckIns,
            $bookingsByCourt,
            $date,
            $dateString,
            $maintenanceByCourt,
            $now,
            $today,
            &$counts
        ): array {
            $items = $bookingsByCourt->get($court->id, collect());
            $maintenance = $maintenanceByCourt->get($court->id, collect())->first();
            $isToday = $dateString === $today->toDateString();
            $status = 'available';
            $statusLabel = 'Đang trống';
            $booking = null;
            $bookingLabel = '';
            $footer = 'Sẵn sàng nhận khách';
            $remainingMinutes = null;
            $progress = 0;

            if (! $court->is_active || $maintenance) {
                $status = 'maintenance';
                $statusLabel = $court->is_active ? 'Đang bảo trì' : 'Ngưng hoạt động';
                $footer = $maintenance?->title ?? 'Sân hiện không hoạt động';
            } else {
                foreach ($items as $item) {
                    if ($isToday && $activeCheckIns->has($court->id.':'.$item->booking_id)) {
                        $booking = $item;
                        break;
                    }
                }

                if ($booking) {
                    $status = 'playing';
                    $statusLabel = 'Đang chơi';
                    $bookingLabel = 'Thời gian chơi';
                    $startAt = CarbonImmutable::parse($dateString.' '.$booking->start_time);
                    $endAt = CarbonImmutable::parse($dateString.' '.$booking->end_time);
                    $duration = max(1, $startAt->diffInMinutes($endAt));
                    $elapsed = max(0, $startAt->diffInMinutes($now, false));
                    $progress = min(100, (int) floor($elapsed / $duration * 100));
                    $remainingMinutes = max(0, (int) ceil(($endAt->getTimestamp() - $now->getTimestamp()) / 60));
                    $footer = 'Còn lại '.$remainingMinutes.' phút';
                } elseif ($date->lt($today) && $items->isNotEmpty()) {
                    $status = 'completed';
                    $statusLabel = 'Đã kết thúc';
                    $booking = $items->last();
                    $bookingLabel = 'Lượt đã chơi';
                    $footer = 'Lịch đặt trong ngày';
                } else {
                    $booking = $items->first(function ($item) use ($dateString, $isToday, $now): bool {
                        if (! $isToday) {
                            return true;
                        }

                        $endAt = CarbonImmutable::parse($dateString.' '.$item->end_time);

                        return $endAt->gt($now);
                    });

                    if ($booking) {
                        $status = 'upcoming';
                        $statusLabel = 'Sắp đến';
                        $bookingLabel = 'Lượt tiếp theo';
                        $startAt = CarbonImmutable::parse($dateString.' '.$booking->start_time);
                        $footer = $isToday && $startAt->lte($now)
                            ? 'Khách đang chờ check-in'
                            : 'Bắt đầu lúc '.substr((string) $booking->start_time, 0, 5);
                    }
                }
            }

            $counts[$status]++;
            $typeName = $court->court_type_name ?: 'Chưa phân loại';
            $paymentLabels = [
                'paid' => 'Đã thanh toán',
                'unpaid' => 'Chưa thanh toán',
                'partial' => 'Đã thanh toán một phần',
            ];

            return [
                'number' => $court->court_number,
                'name' => $court->name,
                'surface' => $court->surface_type,
                'type' => $typeName,
                'status' => $status,
                'status_label' => $statusLabel,
                'booking' => $booking,
                'booking_label' => $bookingLabel,
                'time_range' => $booking
                    ? substr((string) $booking->start_time, 0, 5).' — '.substr((string) $booking->end_time, 0, 5)
                    : null,
                'guest_name' => $booking?->customer_name ?: 'Khách chưa có thông tin',
                'guest_initials' => $booking?->customer_name
                    ? collect(explode(' ', trim($booking->customer_name)))
                        ->filter()
                        ->map(fn (string $part) => mb_substr($part, 0, 1))
                        ->take(2)
                        ->implode('')
                    : 'KH',
                'booking_code' => $booking?->booking_code,
                'payment_label' => $booking
                    ? ($paymentLabels[$booking->payment_status] ?? 'Thanh toán: '.$booking->payment_status)
                    : null,
                'footer' => $footer,
                'maintenance_reason' => $maintenance?->reason,
                'progress' => $progress,
                'remaining_minutes' => $remainingMinutes,
                'search' => mb_strtolower($court->name.' '.$typeName.' '.($booking?->customer_name ?? ''), 'UTF-8'),
            ];
        });

        return view('auth.staff-dashboard', [
            'venue' => $venue,
            'courts' => $courtCards,
            'counts' => $counts,
            'courtTypes' => $courtCards->pluck('type')->unique()->values(),
            'selectedDate' => $date,
            'updatedAt' => $now,
        ]);
    }
}
