<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\PaymentEvent;
use App\Models\PaymentTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    public function createVnpayPaymentUrl(Booking $booking, string $ipAddress): array
    {
        $tmnCode = (string) config('vnpay.tmn_code');
        $secret = (string) config('vnpay.hash_secret');
        if ($tmnCode === '' || $secret === '') {
            throw new RuntimeException('VNPay chưa được cấu hình VNPAY_TMN_CODE và VNPAY_HASH_SECRET.');
        }

        $transactionCode = 'TXN-' . $booking->booking_code . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
        $transaction = PaymentTransaction::create([
            'transaction_code' => $transactionCode,
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'gateway' => 'vnpay',
            'amount' => $booking->final_amount,
            'currency' => 'VND',
            'status' => 'pending',
        ]);

        $params = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $tmnCode,
            'vnp_Amount' => (int) round(((float) $booking->final_amount) * 100),
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $ipAddress,
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toan dat san GoSport: ' . $booking->booking_code,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => (string) config('vnpay.return_url'),
            'vnp_TxnRef' => $transactionCode,
        ];
        ksort($params);

        $query = http_build_query($params, '', '&', PHP_QUERY_RFC1738);
        $signature = hash_hmac('sha512', $query, $secret);
        $url = rtrim((string) config('vnpay.url'), '?') . '?' . $query . '&vnp_SecureHash=' . $signature;

        return ['payment_url' => $url, 'transaction' => $transaction];
    }

    /** Verify signature and amount, then apply the notification once under row locks. */
    public function handleVnpayResponse(array $params, string $eventType, ?string $ipAddress): array
    {
        $providedHash = (string) ($params['vnp_SecureHash'] ?? '');
        $secret = (string) config('vnpay.hash_secret');
        $signedParams = [];
        foreach ($params as $key => $value) {
            if (str_starts_with((string) $key, 'vnp_')
                && !in_array($key, ['vnp_SecureHash', 'vnp_SecureHashType'], true)
                && (is_string($value) || is_numeric($value))) {
                $signedParams[$key] = (string) $value;
            }
        }
        ksort($signedParams);
        $hashData = http_build_query($signedParams, '', '&', PHP_QUERY_RFC1738);
        $expectedHash = hash_hmac('sha512', $hashData, $secret);

        if ($providedHash === '' || !hash_equals(strtolower($expectedHash), strtolower($providedHash))) {
            return $this->rejected('97', 'Invalid signature.');
        }

        if (!hash_equals((string) config('vnpay.tmn_code'), (string) ($params['vnp_TmnCode'] ?? ''))) {
            return $this->rejected('97', 'Invalid terminal code.');
        }

        $transactionCode = (string) ($params['vnp_TxnRef'] ?? '');
        $transaction = PaymentTransaction::query()
            ->where('transaction_code', $transactionCode)
            ->where('gateway', 'vnpay')
            ->first();
        if (!$transaction) {
            return $this->rejected('01', 'Order not found.');
        }

        $reportedAmount = filter_var($params['vnp_Amount'] ?? null, FILTER_VALIDATE_INT);
        $expectedAmount = (int) round(((float) $transaction->amount) * 100);
        if ($reportedAmount === false || $reportedAmount !== $expectedAmount) {
            return $this->rejected('04', 'Invalid amount.');
        }

        return DB::transaction(function () use ($transaction, $params, $eventType, $ipAddress) {
            $locked = PaymentTransaction::query()->lockForUpdate()->findOrFail($transaction->id);
            PaymentEvent::create([
                'payment_transaction_id' => $locked->id,
                'event_type' => $eventType,
                'payload' => $params,
                'ip_address' => $ipAddress,
            ]);

            $responseCode = (string) ($params['vnp_ResponseCode'] ?? '');
            $transactionStatus = (string) ($params['vnp_TransactionStatus'] ?? $responseCode);
            $paid = $responseCode === '00' && $transactionStatus === '00';
            $booking = $locked->booking_id
                ? Booking::query()->lockForUpdate()->find($locked->booking_id)
                : null;

            if ($paid) {
                if ($locked->status !== 'succeeded') {
                    $locked->update([
                        'status' => 'succeeded',
                        'gateway_transaction_id' => $params['vnp_TransactionNo'] ?? null,
                        'gateway_response' => $params,
                        'paid_at' => Carbon::now(),
                    ]);
                    if ($booking) {
                        $booking->update([
                            'status' => 'confirmed',
                            'payment_status' => 'paid',
                            'payment_method' => 'vnpay',
                        ]);
                        $this->createInvoiceOnce($booking);
                    }
                }
            } elseif ($locked->status === 'pending') {
                $locked->update(['status' => 'failed', 'gateway_response' => $params]);
            }

            return [
                'valid' => true,
                'paid' => $locked->status === 'succeeded',
                'response_code' => '00',
                'message' => $locked->status === 'succeeded' ? 'Payment confirmed.' : 'Notification received.',
                'booking' => $booking,
                'transaction' => $locked,
            ];
        });
    }

    private function createInvoiceOnce(Booking $booking): Invoice
    {
        $existing = Invoice::query()->where('booking_id', $booking->id)->first();
        if ($existing) {
            return $existing;
        }

        $user = $booking->user_id ? \App\Models\User::find($booking->user_id) : null;
        return Invoice::create([
            'invoice_code' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8)),
            'booking_id' => $booking->id,
            'customer_name' => $user?->name,
            'customer_phone' => $user?->phone,
            'subtotal' => $booking->final_amount,
            'tax_amount' => 0,
            'total' => $booking->final_amount,
            'payment_method' => 'vnpay',
            'status' => 'paid',
        ]);
    }

    private function rejected(string $code, string $message): array
    {
        return ['valid' => false, 'paid' => false, 'response_code' => $code, 'message' => $message];
    }
}
