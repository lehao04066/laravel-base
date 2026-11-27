<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class VnpayService
{
    public function createPaymentUrl(Booking $booking, string $ipAddress): string
    {
        $tmnCode = (string) config('services.vnpay.tmn_code');
        $secret = (string) config('services.vnpay.hash_secret');
        $returnUrl = config('services.vnpay.return_url') ?: route('vnpay.return');
        if ($tmnCode === '' || $secret === '') {
            throw new RuntimeException('Chưa cấu hình VNPAY_TMN_CODE và VNPAY_HASH_SECRET.');
        }
        if ($booking->payment_status === 'paid' || $booking->status === 'cancelled') {
            throw new RuntimeException('Đơn này không còn đủ điều kiện thanh toán.');
        }
        $amount = (int) round((float) $booking->final_amount);
        if ($amount < 1 || abs($amount - (float) $booking->final_amount) > 0.001) {
            throw new RuntimeException('Số tiền thanh toán không hợp lệ.');
        }

        $transaction = PaymentTransaction::query()
            ->where('booking_id', $booking->id)->where('gateway', 'vnpay')
            ->where('status', 'pending')->latest('id')->first();
        if (! $transaction) {
            $transaction = PaymentTransaction::create([
                'transaction_code' => 'GS'.$booking->id.'-'.Str::upper(Str::random(12)),
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'gateway' => 'vnpay',
                'amount' => $amount,
                'currency' => 'VND',
                'status' => 'pending',
            ]);
        }

        $params = [
            'vnp_Version' => config('services.vnpay.version'),
            'vnp_Command' => config('services.vnpay.command'),
            'vnp_TmnCode' => $tmnCode,
            'vnp_Amount' => (string) ((int) round((float) $transaction->amount) * 100),
            'vnp_CurrCode' => config('services.vnpay.curr_code'),
            'vnp_TxnRef' => $transaction->transaction_code,
            'vnp_OrderInfo' => 'Thanh toan don dat san '.$booking->booking_code,
            'vnp_OrderType' => config('services.vnpay.order_type'),
            'vnp_Locale' => config('services.vnpay.locale'),
            'vnp_ReturnUrl' => $returnUrl,
            'vnp_IpAddr' => $ipAddress,
            'vnp_CreateDate' => now()->format('YmdHis'),
        ];
        ksort($params);
        // VNPay's v2.1.0 PHP sample uses urlencode() for both the signed
        // payload and the outgoing query string (spaces become '+').
        $query = $this->canonicalQuery($params);
        $signature = hash_hmac('sha512', $query, $secret);

        $transaction->update(['gateway_response' => ['request' => $params]]);
        return rtrim((string) config('services.vnpay.url'), '?').'?'.$query.'&vnp_SecureHash='.$signature;
    }

    public function signedParams(array $input): array
    {
        $params = collect($input)->filter(fn ($value, $key) => str_starts_with((string) $key, 'vnp_')
            && ! in_array($key, ['vnp_SecureHash', 'vnp_SecureHashType'], true))
            ->all();
        ksort($params);
        return $params;
    }

    public function verifySignature(array $input): bool
    {
        $secret = (string) config('services.vnpay.hash_secret');
        $received = (string) ($input['vnp_SecureHash'] ?? '');
        if ($secret === '' || $received === '') return false;
        $query = $this->canonicalQuery($this->signedParams($input));
        return hash_equals(hash_hmac('sha512', $query, $secret), strtolower($received));
    }

    private function canonicalQuery(array $params): string
    {
        return collect($params)
            ->map(fn ($value, $key) => urlencode((string) $key).'='.urlencode((string) $value))
            ->implode('&');
    }

    /** Process a verified callback exactly once; return VNPay's response code. */
    public function processCallback(array $input, string $eventType, ?string $ipAddress): string
    {
        if (! $this->verifySignature($input)) return '97';
        $transaction = PaymentTransaction::where('transaction_code', (string) ($input['vnp_TxnRef'] ?? ''))->first();
        if (! $transaction) return '01';
        $expectedAmount = (int) round((float) $transaction->amount) * 100;
        if ((int) ($input['vnp_Amount'] ?? -1) !== $expectedAmount) return '04';

        return DB::transaction(function () use ($transaction, $input, $eventType, $ipAddress) {
            $payment = PaymentTransaction::whereKey($transaction->id)->lockForUpdate()->first();
            $booking = $payment->booking_id ? Booking::whereKey($payment->booking_id)->lockForUpdate()->first() : null;
            DB::table('payment_events')->insert([
                'payment_transaction_id' => $payment->id,
                'event_type' => $eventType,
                'payload' => json_encode($input, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ip_address' => $ipAddress,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            if (! $booking) return '01';
            if ($payment->status === 'succeeded' || $booking->payment_status === 'paid') return '02';

            $success = ($input['vnp_ResponseCode'] ?? '') === '00'
                && ($input['vnp_TransactionStatus'] ?? '') === '00';
            $payment->gateway_transaction_id = isset($input['vnp_TransactionNo']) ? (string) $input['vnp_TransactionNo'] : null;
            $payment->gateway_response = $input;
            if ($success) {
                $payment->status = 'succeeded';
                $payment->paid_at = now();
                $booking->payment_status = 'paid';
                $booking->payment_method = 'vnpay';
                if ($booking->status === 'pending_payment') $booking->status = 'confirmed';
                $booking->save();
                DB::table('invoices')->insertOrIgnore([
                    'invoice_code' => 'INV-'.now()->format('Ymd').'-'.strtoupper(Str::random(8)),
                    'booking_id' => $booking->id,
                    'payment_transaction_id' => $payment->id,
                    'customer_name' => $booking->user?->name,
                    'customer_phone' => $booking->user?->phone,
                    'subtotal' => $booking->total_amount,
                    'tax_amount' => 0,
                    'total' => $booking->final_amount,
                    'payment_method' => 'vnpay',
                    'status' => 'paid',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            } else {
                $payment->status = 'failed';
            }
            $payment->save();
            return '00';
        });
    }
}
