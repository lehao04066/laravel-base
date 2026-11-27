<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\VnpayService;
use Illuminate\Http\Request;
use Throwable;

class VnpayController extends Controller
{
    public function __construct(private VnpayService $vnpay) {}

    public function create(string|int $id, Request $request)
    {
        $booking = Booking::where(is_numeric($id) ? 'id' : 'booking_code', $id)
            ->where('user_id', $request->user()->id)->firstOrFail();
        try {
            return redirect()->away($this->vnpay->createPaymentUrl($booking, $request->ip()));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function return(Request $request)
    {
        $input = $request->query();
        $valid = $this->vnpay->verifySignature($input);
        $code = $valid ? $this->vnpay->processCallback($input, 'callback_received', $request->ip()) : '97';
        return redirect()->route('my-bookings')->with($code === '00' || $code === '02' ? 'success' : 'error',
            $code === '00' ? 'Thanh toán VNPay thành công, hóa đơn đã được tạo.'
                : ($code === '02' ? 'Giao dịch này đã được ghi nhận trước đó.' : 'Thanh toán chưa thành công hoặc dữ liệu xác thực không hợp lệ.'));
    }

    public function ipn(Request $request)
    {
        $code = $this->vnpay->processCallback($request->query(), 'ipn_received', $request->ip());
        return response()->json(['RspCode' => $code, 'Message' => match ($code) {
            '00' => 'Confirm Success', '01' => 'Order not found', '02' => 'Order already confirmed',
            '04' => 'Invalid amount', '97' => 'Invalid signature', default => 'Unknown error',
        }]);
    }
}
