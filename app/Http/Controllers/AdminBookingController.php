<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'venue', 'items.court'])->latest();

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by venue
        if ($request->filled('venue_id') && $request->venue_id !== 'all') {
            $query->where('venue_id', $request->venue_id);
        }

        // Search by code, customer name, phone
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('booking_code', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                          ->orWhere('phone', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $venues = Venue::all();

        // Summary counts
        $totalCount = Booking::count();
        $confirmedCount = Booking::where('status', 'confirmed')->count();
        $cancelledCount = Booking::where('status', 'cancelled')->count();
        $todayBookingsCount = Booking::whereDate('booking_date', Carbon::today())->count();

        return view('admin.bookings.index', compact(
            'bookings',
            'venues',
            'totalCount',
            'confirmedCount',
            'cancelledCount',
            'todayBookingsCount'
        ));
    }

    public function show(string|int $id)
    {
        $booking = is_numeric($id)
            ? Booking::with(['user', 'venue', 'items.court', 'statusHistories.user'])->findOrFail((int) $id)
            : Booking::with(['user', 'venue', 'items.court', 'statusHistories.user'])->where('booking_code', $id)->firstOrFail();

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, string|int $id)
    {
        $booking = is_numeric($id) 
            ? Booking::findOrFail((int) $id) 
            : Booking::where('booking_code', $id)->firstOrFail();

        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $reason = $request->input('reason') ?: $request->input('cancellation_reason') ?: 'Quản trị viên hủy đơn';

        $result = $this->bookingService->cancelBooking(
            $booking,
            auth()->user(),
            'Admin hủy: ' . $reason
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
