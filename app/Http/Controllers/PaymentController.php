<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $payments)
    {
    }

    public function showVnpayPaymentForm(string $bookingCode): View
    {
        $booking = Booking::query()
            ->where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('payment.vnpay', compact('booking'));
    }

    public function createVnpayPaymentUrl(string $bookingCode, Request $request): RedirectResponse
    {
        $booking = Booking::query()
            ->where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        abort_if($booking->payment_status === 'paid', 409, 'Booking has already been paid.');
        abort_unless((float) $booking->final_amount > 0, 422, 'Booking amount must be greater than zero.');

        $result = $this->payments->createVnpayPaymentUrl($booking, (string) $request->ip());
        return redirect()->away($result['payment_url']);
    }

    public function vnpayReturn(Request $request): RedirectResponse
    {
        $result = $this->payments->handleVnpayResponse($request->query(), 'return_received', $request->ip());
        if (!$result['valid']) {
            return redirect('/')->with('payment_error', $result['message']);
        }

        return redirect('/')->with(
            $result['paid'] ? 'payment_success' : 'payment_error',
            $result['paid'] ? 'Thanh toán VNPay thành công.' : 'Giao dịch VNPay chưa thành công.'
        );
    }

    public function vnpayIpn(Request $request): JsonResponse
    {
        $result = $this->payments->handleVnpayResponse($request->all(), 'ipn_received', $request->ip());
        return response()->json([
            'RspCode' => $result['response_code'],
            'Message' => $result['valid'] ? 'Confirm Success' : $result['message'],
        ]);
    }
}
