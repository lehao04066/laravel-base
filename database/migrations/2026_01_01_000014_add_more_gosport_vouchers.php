<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('vouchers')->updateOrInsert(
            ['code' => 'GOFIRST10'],
            [
                'title' => 'Giảm 10% cho lượt đặt sân tiếp theo',
                'discount_type' => 'percent',
                'discount_value' => 10.00,
                'min_order_amount' => 100000.00,
                'max_discount_amount' => 50000.00,
                'usage_limit' => 1000,
                'used_count' => 0,
                'start_date' => '2026-11-25',
                'end_date' => '2026-12-31',
                'is_active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('vouchers')->updateOrInsert(
            ['code' => 'SMASH30K'],
            [
                'title' => 'Giảm ngay 30.000đ cho đơn đặt sân',
                'discount_type' => 'fixed',
                'discount_value' => 30000.00,
                'min_order_amount' => 150000.00,
                'max_discount_amount' => null,
                'usage_limit' => 500,
                'used_count' => 0,
                'start_date' => '2026-11-25',
                'end_date' => '2026-12-31',
                'is_active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        DB::table('vouchers')
            ->whereIn('code', ['GOFIRST10', 'SMASH30K'])
            ->delete();
    }
};
