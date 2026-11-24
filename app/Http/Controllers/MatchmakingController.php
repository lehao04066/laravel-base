<?php

namespace App\Http\Controllers;

use App\Models\MatchParticipant;
use App\Models\MatchPost;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MatchmakingController extends Controller
{
    private const LEVELS = [
        'beginner',
        'intermediate_minus',
        'intermediate',
        'intermediate_plus',
        'advanced',
    ];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'level' => ['nullable', Rule::in(['all', ...self::LEVELS])],
            'venue_id' => ['nullable', 'integer', Rule::exists('venues', 'id')->where('is_active', true)],
            'play_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'max_fee' => ['nullable', 'numeric', 'min:0', 'max:2000000'],
        ]);

        $query = MatchPost::query()
            ->with(['user', 'venue', 'participants.user'])
            ->whereIn('status', ['open', 'full'])
            ->where('play_datetime', '>=', now())
            ->orderBy('play_datetime');

        if (! empty($filters['level']) && $filters['level'] !== 'all') {
            $query->where('target_level', $filters['level']);
        }

        if (! empty($filters['venue_id'])) {
            $query->where('venue_id', $filters['venue_id']);
        }

        if (! empty($filters['play_date'])) {
            $query->whereDate('play_datetime', $filters['play_date']);
        }

        if (isset($filters['max_fee']) && $filters['max_fee'] !== '') {
            $query->where('fee_per_player', '<=', $filters['max_fee']);
        }

        $matches = $query->paginate(9)->withQueryString();
        $venues = Venue::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'district']);

        return view('customer.matchmaking', [
            'matches' => $matches,
            'venues' => $venues,
            'levelLabels' => [
                'beginner' => 'Mới chơi',
                'intermediate_minus' => 'Trung bình thấp (TB-)',
                'intermediate' => 'Trung bình (TB)',
                'intermediate_plus' => 'Trung bình khá (TB+)',
                'advanced' => 'Nâng cao',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'venue_id' => ['required', 'integer', Rule::exists('venues', 'id')->where('is_active', true)],
            'play_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'play_time' => ['required', 'date_format:H:i'],
            'duration_hours' => ['required', 'numeric', Rule::in(['1', '1.5', '2', '2.5', '3', '4'])],
            'target_level' => ['required', Rule::in(self::LEVELS)],
            'players_needed' => ['required', 'integer', 'min:2', 'max:12'],
            'fee_per_player' => ['required', 'numeric', 'min:0', 'max:2000000'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'venue_id.exists' => 'Vui lòng chọn cụm sân đang hoạt động.',
            'play_date.after_or_equal' => 'Ngày chơi phải từ hôm nay trở đi.',
            'duration_hours.in' => 'Vui lòng chọn thời lượng hợp lệ.',
            'target_level.in' => 'Vui lòng chọn trình độ hợp lệ.',
        ]);

        $playDatetime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['play_date'].' '.$validated['play_time'],
        );

        if ($playDatetime->lessThanOrEqualTo(now())) {
            return back()
                ->withErrors(['play_time' => 'Giờ chơi phải ở phía trước thời điểm hiện tại.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $validated, $playDatetime): void {
            $match = MatchPost::create([
                'user_id' => $request->user()->id,
                'venue_id' => $validated['venue_id'],
                'title' => $validated['title'],
                'play_datetime' => $playDatetime,
                'duration_hours' => $validated['duration_hours'],
                'target_level' => $validated['target_level'],
                'players_needed' => $validated['players_needed'],
                'players_joined' => 1,
                'fee_per_player' => $validated['fee_per_player'],
                'description' => $validated['description'] ?? null,
                'status' => 'open',
            ]);

            $match->participants()->create([
                'user_id' => $request->user()->id,
                'status' => 'joined',
                'deposit_status' => 'unpaid',
            ]);
        });

        return redirect()->route('matchmaking.index')
            ->with('success', 'Đã đăng kèo. Bạn đã được thêm vào phòng giao lưu.');
    }

    public function join(int $id, Request $request): RedirectResponse
    {
        try {
            $result = DB::transaction(function () use ($id, $request): ?string {
                $match = MatchPost::query()->whereKey($id)->lockForUpdate()->firstOrFail();

                if ((int) $match->user_id === (int) $request->user()->id) {
                    return 'Bạn đã là người tạo kèo này.';
                }

                if ($match->play_datetime->lessThanOrEqualTo(now())) {
                    return 'Kèo này đã qua thời gian chơi.';
                }

                if ($match->status !== 'open') {
                    return 'Kèo này hiện không nhận thêm người tham gia.';
                }

                $participant = MatchParticipant::query()
                    ->where('match_post_id', $match->id)
                    ->where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->first();

                if ($participant?->status === 'joined') {
                    return 'Bạn đã tham gia kèo này rồi.';
                }

                $joinedCount = $match->participants()->where('status', 'joined')->count();
                if ($joinedCount >= $match->players_needed) {
                    $match->update(['players_joined' => $joinedCount, 'status' => 'full']);

                    return 'Kèo này đã đủ người tham gia.';
                }

                if ($participant) {
                    $participant->update(['status' => 'joined', 'deposit_status' => 'unpaid']);
                } else {
                    $match->participants()->create([
                        'user_id' => $request->user()->id,
                        'status' => 'joined',
                        'deposit_status' => 'unpaid',
                    ]);
                }

                $joinedCount++;
                $match->update([
                    'players_joined' => $joinedCount,
                    'status' => $joinedCount >= $match->players_needed ? 'full' : 'open',
                ]);

                return null;
            });
        } catch (QueryException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }

            return back()->with('error', 'Bạn đã tham gia kèo này rồi.');
        }

        if ($result !== null) {
            return back()->with('error', $result);
        }

        return back()->with('success', 'Đã tham gia phòng giao lưu. Hẹn gặp bạn tại sân!');
    }
}
