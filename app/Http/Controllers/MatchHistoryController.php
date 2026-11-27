<?php

namespace App\Http\Controllers;

use App\Models\MatchResult;
use App\Models\UserProfile;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MatchHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $results = MatchResult::with('venue')->where('user_id', $request->user()->id)->latest('played_at')->paginate(10);
        $venues = Venue::where('is_active', true)->orderBy('name')->get();
        $summary = [
            'total' => MatchResult::where('user_id', $request->user()->id)->count(),
            'won' => MatchResult::where('user_id', $request->user()->id)->where('result', 'won')->count(),
            'lost' => MatchResult::where('user_id', $request->user()->id)->where('result', 'lost')->count(),
        ];
        $summary['win_rate'] = $summary['total'] > 0 ? round($summary['won'] / $summary['total'] * 100, 1) : 0;
        return view('customer.match_history', compact('results', 'venues', 'summary'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'venue_id' => ['nullable', 'exists:venues,id'],
            'opponent_name' => ['required', 'string', 'max:255'],
            'score' => ['nullable', 'string', 'max:100'],
            'result' => ['required', 'in:won,lost,draw'],
            'played_at' => ['required', 'date', 'before_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $data) {
            MatchResult::create($data + ['user_id' => $request->user()->id]);
            $total = MatchResult::where('user_id', $request->user()->id)->count();
            $won = MatchResult::where('user_id', $request->user()->id)->where('result', 'won')->count();
            UserProfile::updateOrCreate(
                ['user_id' => $request->user()->id],
                ['matches_played' => $total, 'win_rate' => $total > 0 ? round($won / $total * 100, 2) : 0]
            );
        });

        return back()->with('success', 'Đã thêm kết quả trận đấu và cập nhật thống kê hồ sơ.');
    }
}
