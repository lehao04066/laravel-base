<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Services\BookingService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
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
        } elseif ($tab === 'completed') {
            $query->whereIn('status', ['checked_in', 'completed']);
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $bookings = $query->paginate(10);

        // Counts for tabs
        $upcomingCount = Booking::where('user_id', $user->id)->where('status', 'confirmed')->count();
        $completedCount = Booking::where('user_id', $user->id)->whereIn('status', ['checked_in', 'completed'])->count();
        $cancelledCount = Booking::where('user_id', $user->id)->where('status', 'cancelled')->count();
        $allCount = Booking::where('user_id', $user->id)->count();

        return view('customer.my_bookings', compact('bookings', 'tab', 'upcomingCount', 'completedCount', 'cancelledCount', 'allCount'));
    }

    public function show(string|int $id)
    {
        // Enforce that client can ONLY see their own booking
        $booking = Booking::with(['items.court', 'venue', 'statusHistories.user', 'review.reply'])
            ->where(is_numeric($id) ? 'id' : 'booking_code', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.booking_detail', compact('booking'));
    }

    public function submitReview(Request $request, string|int $id)
    {
        $booking = Booking::with('venue')
            ->where(is_numeric($id) ? 'id' : 'booking_code', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (! in_array($booking->status, ['checked_in', 'completed'], true)) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sau khi đã chơi xong.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rating.required' => 'Vui lòng chọn số sao.',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá.',
        ]);

        Review::updateOrCreate(
            ['booking_id' => $booking->id, 'user_id' => Auth::id()],
            ['venue_id' => $booking->venue_id, 'rating' => $data['rating'], 'comment' => $data['comment'], 'is_approved' => false]
        );

        return back()->with('success', 'Đã gửi đánh giá. Đánh giá sẽ hiển thị sau khi được duyệt.');
    }

    public function qr(string|int $id)
    {
        $booking = Booking::where(is_numeric($id) ? 'id' : 'booking_code', $id)
            ->where('user_id', Auth::id())
            ->whereNotNull('check_in_token')
            ->firstOrFail();

        $result = (new PngWriter())->write(new QrCode(
            data: $booking->check_in_token,
            size: 260,
            margin: 12,
        ));

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Cache-Control' => 'private, no-store',
        ]);
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
