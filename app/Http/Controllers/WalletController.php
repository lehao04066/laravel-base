<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\WalletAccount;
use App\Models\WalletLedgerEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return view('customer.profile', compact('user', 'profile', 'wallet', 'ledgerEntries'));
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
                'description' => 'Nạp thử '.number_format($amount, 0, ',', '.').'đ, tặng 15%',
                'reference_id' => 'DEMO-'.Str::uuid(),
            ]);
        });

        return back()->with('success', 'Đã cộng số dư ví thử nghiệm, gồm 15% thưởng.');
    }
}
