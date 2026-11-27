<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\WalletAccount;
use App\Models\WalletLedgerEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile()->firstOrCreate([], [
            'skill_level' => 'intermediate',
            'play_style' => 'Tấn công Smash',
        ]);
        $wallet = $user->wallet()->firstOrCreate([], [
            'balance' => 0,
            'frozen_balance' => 0,
            'currency' => 'VND',
            'status' => 'active',
        ]);

        $ledgerEntries = Schema::hasTable('wallet_ledger_entries')
            ? $wallet->ledgerEntries()->latest()->take(8)->get()
            : collect();
        $savedVouchers = Schema::hasTable('user_vouchers')
            ? $user->savedVouchers()
                ->where('vouchers.is_active', true)
                ->whereDate('vouchers.start_date', '<=', now())
                ->whereDate('vouchers.end_date', '>=', now())
                ->orderBy('vouchers.end_date')
                ->get()
            : collect();

        return view('customer.profile', compact('user', 'profile', 'wallet', 'ledgerEntries', 'savedVouchers'));
    }

    public function topup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'in:200000,500000,1000000,2000000'],
        ], [
            'amount.required' => 'Vui lòng chọn số tiền muốn nạp.',
            'amount.in' => 'Mức nạp tiền không hợp lệ.',
        ]);

        if (! Schema::hasTable('wallet_ledger_entries')) {
            return back()->withErrors([
                'amount' => 'Chức năng lịch sử ví chưa được cấu hình trong cơ sở dữ liệu.',
            ]);
        }

        $amount = (int) $validated['amount'];
        $bonus = (int) round($amount * 0.15);

        DB::transaction(function () use ($request, $amount, $bonus): void {
            $user = $request->user();
            $wallet = $user->wallet()->lockForUpdate()->first();

            if (! $wallet) {
                $wallet = $user->wallet()->create([
                    'balance' => 0,
                    'frozen_balance' => 0,
                    'currency' => 'VND',
                    'status' => 'active',
                ]);
            }

            $balanceAfter = (float) $wallet->balance + $amount + $bonus;
            $wallet->update(['balance' => $balanceAfter]);

            WalletLedgerEntry::create([
                'wallet_account_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => $amount + $bonus,
                'balance_after' => $balanceAfter,
                'description' => 'Nạp tiền '.number_format($amount, 0, ',', '.').'đ, tặng 15%',
                'reference_id' => 'DEMO-'.Str::uuid(),
            ]);
        });

        return back()->with('success', 'Đã cộng số dư ví thử nghiệm, gồm 15% thưởng.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'skill_level' => ['required', 'string', 'max:50'],
            'play_style' => ['nullable', 'string', 'max:100'],
            'preferred_hand' => ['required', 'in:right,left'],
            'racket_brand' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $user->update(['name' => $data['name'], 'phone' => $data['phone']]);
        $user->profile()->updateOrCreate([], [
            'skill_level' => $data['skill_level'],
            'play_style' => $data['play_style'],
            'preferred_hand' => $data['preferred_hand'],
            'racket_brand' => $data['racket_brand'],
            'bio' => $data['bio'],
        ]);

        return back()->with('success', 'Đã cập nhật hồ sơ cá nhân.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.current_password' => 'Mật khẩu hiện tại không đúng.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Đã đổi mật khẩu thành công.');
    }

}
