<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherRedemption;

class VoucherService
{
    public function applyVoucher(string $code, float $orderAmount, ?int $userId = null): array
    {
        $voucher = Voucher::where('code', strtoupper(trim($code)))
            ->where('is_active', true)
            ->first();

        if (! $voucher) {
            return $this->invalid('Mã ưu đãi không tồn tại hoặc đã hết hiệu lực.');
        }

        if (! $voucher->isValidFor($orderAmount)) {
            if ($orderAmount < (float) $voucher->min_order_amount) {
                return $this->invalid('Đơn hàng tối thiểu '.number_format($voucher->min_order_amount, 0, ',', '.').'đ để dùng mã này.');
            }

            if ($voucher->used_count >= $voucher->usage_limit) {
                return $this->invalid('Mã ưu đãi đã hết lượt sử dụng.');
            }

            return $this->invalid('Mã ưu đãi chưa đến ngày dùng hoặc đã hết hạn.');
        }

        if ($userId && VoucherRedemption::where('voucher_id', $voucher->id)->where('user_id', $userId)->exists()) {
            return $this->invalid('Bạn đã sử dụng mã ưu đãi này rồi.');
        }

        return [
            'valid' => true,
            'message' => 'Áp dụng mã ưu đãi thành công!',
            'discount' => $voucher->calculateDiscount($orderAmount),
            'voucher' => $voucher,
        ];
    }

    private function invalid(string $message): array
    {
        return [
            'valid' => false,
            'message' => $message,
            'discount' => 0.00,
            'voucher' => null,
        ];
    }
}
