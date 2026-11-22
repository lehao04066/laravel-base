<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Venue;
use App\Services\BookingService;
use App\Services\CourtAvailabilityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtBookingController extends Controller
{
    protected CourtAvailabilityService $availabilityService;
    protected BookingService $bookingService;

    public function __construct(
        CourtAvailabilityService $availabilityService,
        BookingService $bookingService
    ) {
        $this->availabilityService = $availabilityService;
        $this->bookingService = $bookingService;
    }

    public function showVenue(string $slug, Request $request)
    {
        $venue = Venue::with(['courts.courtType', 'pricingRules'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $selectedDate = $request->query('date', Carbon::today()->toDateString());
        if (Carbon::parse($selectedDate)->lt(Carbon::today())) {
            $selectedDate = Carbon::today()->toDateString();
        }

        $courts = $venue->courts()->where('is_active', true)->get();

        // Generate slots for each court
        $courtSlotsData = [];
        foreach ($courts as $court) {
            $slots = $this->availabilityService->getCourtSlots($court, $selectedDate);
            $courtSlotsData[$court->id] = $slots;
        }

        return view('customer.venue_detail', compact('venue', 'courts', 'selectedDate', 'courtSlotsData'));
    }

    public function getSlotsApi(int $courtId, Request $request)
    {
        $court = Court::with('venue')->findOrFail($courtId);
        $date = $request->query('date', Carbon::today()->toDateString());

        $slots = $this->availabilityService->getCourtSlots($court, $date);

        return response()->json([
            'success' => true,
            'court' => $court,
            'date' => $date,
            'slots' => $slots,
        ]);
    }

    public function submitBooking(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiến hành đặt sân.');
        }

        $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.court_id' => ['required', 'exists:courts,id'],
            'slots.*.start_time' => ['required', 'string'],
            'slots.*.end_time' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'slots.required' => 'Vui lòng chọn ít nhất một khung giờ sân để đặt.',
            'booking_date.after_or_equal' => 'Không thể đặt sân cho ngày trong quá khứ.',
        ]);

        $venue = Venue::where('is_active', true)->findOrFail($request->venue_id);
        $user = Auth::user();
        $date = $request->booking_date;
        $slots = $request->slots;

        try {
            $booking = $this->bookingService->createBooking(
                $user,
                $venue,
                $date,
                $slots,
                $request->notes
            );

            return redirect()->route('my-bookings.show', $booking->booking_code)
                ->with('success', "Đặt sân thành công! Mã đặt sân của bạn là: {$booking->booking_code}. Hình thức: Thanh toán tại sân.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
