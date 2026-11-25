<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        $vouchers = Voucher::query()
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->whereColumn('used_count', '<', 'usage_limit')
            ->orderBy('end_date')
            ->get();

        return view('customer.promotions', compact('vouchers'));
    }

    public function saveVoucher(int $id): RedirectResponse
    {
        $voucher = Voucher::where('is_active', true)->findOrFail($id);
        request()->user()->savedVouchers()->syncWithoutDetaching([
            $voucher->id => ['saved_at' => now()],
        ]);

        return back()->with('success', "Đã lưu mã {$voucher->code} vào ví ưu đãi của bạn!");
    }
}
