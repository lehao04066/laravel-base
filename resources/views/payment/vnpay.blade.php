<!doctype html>
<html lang="vi">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Thanh toán VNPay</title></head>
<body style="font-family:system-ui,sans-serif;max-width:560px;margin:4rem auto;padding:1rem">
    <h1>Thanh toán đặt sân</h1>
    <p>Mã đặt sân: <strong>{{ $booking->booking_code }}</strong></p>
    <p>Số tiền: <strong>{{ number_format((float) $booking->final_amount, 0, ',', '.') }} ₫</strong></p>
    <form method="post" action="{{ route('payment.vnpay.create', $booking->booking_code) }}">
        @csrf
        <button type="submit">Tiếp tục đến VNPay</button>
    </form>
</body>
</html>
