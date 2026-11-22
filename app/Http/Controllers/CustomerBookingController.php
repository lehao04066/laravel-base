<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'upcoming');

        $query = Booking::with(['items.court', 'venue'])
            ->where('user_id', $user->id)
            ->latest();

        if ($tab === 'upcoming') {
            $query->where('status', 'confirmed');
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $bookings = $query->paginate(10);

        // Counts for tabs
        $upcomingCount = Booking::where('user_id', $user->id)->where('status', 'confirmed')->count();
        $cancelledCount = Booking::where('user_id', $user->id)->where('status', 'cancelled')->count();
        $allCount = Booking::where('user_id', $user->id)->count();

        return view('customer.my_bookings', compact('bookings', 'tab', 'upcomingCount', 'cancelledCount', 'allCount'));
    }

    public function show(string|int $id)
    {
        // Enforce that client can ONLY see their own booking
        $booking = Booking::with(['items.court', 'venue', 'statusHistories.user'])
            ->where(is_numeric($id) ? 'id' : 'booking_code', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.booking_detail', compact('booking'));
    }

    public function cancel(string|int $id, Request $request)
    {
        $booking = Booking::where(is_numeric($id) ? 'id' : 'booking_code', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ], [
            'reason.required' => 'Vui lòng nhập lý do hủy đặt sân.',
        ]);

        $result = $this->bookingService->cancelBooking($booking, Auth::user(), 'Khách hàng hủy: ' . $request->reason);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
