<?php

namespace App\Http\Controllers;

use App\Models\MatchPost;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MatchmakingController extends Controller
{
    public function index(Request $request)
    {
        $query = MatchPost::with(['user', 'venue'])
            ->where('status', 'open')
            ->where('play_datetime', '>=', now())
            ->latest('play_datetime');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($builder) use ($keyword) {
                $builder->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhereHas('venue', fn ($venue) => $venue->where('name', 'like', "%{$keyword}%"));
            });
        }

        if ($request->filled('level') && $request->string('level')->toString() !== 'all') {
            $query->where('target_level', $request->string('level')->toString());
        }

        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->integer('venue_id'));
        }

        if ($request->filled('play_date')) {
            $query->whereDate('play_datetime', $request->date('play_date'));
        }

        if ($request->filled('max_fee') && is_numeric($request->input('max_fee'))) {
            $query->where('fee_per_player', '<=', (float) $request->input('max_fee'));
        }

        $posts = $query->paginate(9)->withQueryString();
        $venues = Venue::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('customer.matchmaking', compact('posts', 'venues'));
    }

    public function show(MatchPost $matchPost)
    {
        $matchPost->load(['user', 'venue', 'participants.user']);

        return view('customer.matchmaking_room', compact('matchPost'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'title' => ['required', 'string', 'max:255'],
            'match_type' => ['required', 'in:singles,doubles'],
            'play_datetime' => ['required', 'date', 'after:now'],
            'duration_hours' => ['required', 'numeric', 'min:0.5', 'max:8'],
            'target_level' => ['required', 'in:all,beginner,intermediate,intermediate_plus,advanced'],
            'players_needed' => ['required', 'integer', 'min:2', 'max:20'],
            'fee_per_player' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $post = DB::transaction(function () use ($data) {
            $post = MatchPost::create(array_merge($data, [
                'user_id' => Auth::id(),
                'players_joined' => 1,
                'fee_per_player' => $data['fee_per_player'] ?? 0,
                'status' => 'open',
            ]));

            $post->participants()->create([
                'user_id' => Auth::id(),
                'status' => 'joined',
            ]);

            return $post;
        });

        return redirect()->route('matchmaking.show', $post)->with('success', 'Đã đăng bài ghép kèo thành công.');
    }

    public function join(MatchPost $matchPost)
    {
        if ($matchPost->user_id === Auth::id()) {
            return back()->with('error', 'Bạn là người tạo bài ghép kèo này.');
        }

        $joined = DB::transaction(function () use ($matchPost) {
            $post = MatchPost::whereKey($matchPost->id)->lockForUpdate()->firstOrFail();

            if ($post->status !== 'open' || $post->play_datetime->isPast() || $post->players_joined >= $post->players_needed) {
                return false;
            }

            if ($post->participants()->where('user_id', Auth::id())->exists()) {
                return false;
            }

            $post->participants()->create([
                'user_id' => Auth::id(),
                'status' => 'joined',
            ]);
            $post->increment('players_joined');

            if ($post->players_joined >= $post->players_needed) {
                $post->update(['status' => 'full']);
            }

            return true;
        });

        return redirect()->route('matchmaking.show', $matchPost)->with($joined ? 'success' : 'error', $joined
            ? 'Bạn đã tham gia kèo thành công.'
            : 'Kèo này đã đủ người hoặc bạn đã tham gia rồi.');
    }
}
