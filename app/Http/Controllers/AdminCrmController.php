<?php

namespace App\Http\Controllers;

use App\Models\MembershipTier;
use App\Models\RecurringContract;
use App\Models\User;
use App\Models\UserMembership;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCrmController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $users = User::query()
            ->whereIn('role', ['customer', 'client'])
            ->with(['memberships' => fn ($q) => $q->where('is_active', true)->with('tier')])
            ->withCount('bookings')
            ->when($search !== '', fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            }))
            ->latest()->paginate(12)->withQueryString();

        $tiers = MembershipTier::orderBy('min_spending')->get();
        $venues = Venue::with('courts')->where('is_active', true)->get();
        $contracts = RecurringContract::with(['user', 'venue', 'court'])->latest()->paginate(10, ['*'], 'contracts_page');
        $stats = [
            'members' => UserMembership::where('is_active', true)->count(),
            'vip' => UserMembership::where('is_active', true)->whereHas('tier', fn ($q) => $q->where('slug', '!=', 'standard'))->count(),
            'contracts' => RecurringContract::where('status', 'active')->count(),
        ];

        return view('admin.crm', compact('users', 'tiers', 'venues', 'contracts', 'stats', 'search'));
    }

    public function assignMembership(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'membership_tier_id' => ['required', 'exists:membership_tiers,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        DB::transaction(function () use ($data) {
            UserMembership::where('user_id', $data['user_id'])->where('is_active', true)->update(['is_active' => false]);
            UserMembership::create($data + ['is_active' => true]);
        });

        return back()->with('success', 'Đã cập nhật hạng thành viên cho khách hàng.');
    }

    public function storeContract(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'], 'venue_id' => ['required', 'exists:venues,id'],
            'court_id' => ['nullable', 'exists:courts,id'], 'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'], 'end_time' => ['required', 'date_format:H:i'],
            'start_date' => ['required', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'weekly_amount' => ['required', 'numeric', 'min:0'], 'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['end_time'] <= $data['start_time']) {
            return back()
                ->withErrors(['end_time' => 'Giờ kết thúc phải sau giờ bắt đầu.'])
                ->withInput();
        }

        RecurringContract::create($data + ['status' => 'active']);
        return back()->with('success', 'Đã tạo hợp đồng đặt sân định kỳ.');
    }

    public function toggleContract(RecurringContract $contract)
    {
        $contract->update(['status' => $contract->status === 'active' ? 'paused' : 'active']);
        return back()->with('success', 'Đã cập nhật trạng thái hợp đồng định kỳ.');
    }
}
