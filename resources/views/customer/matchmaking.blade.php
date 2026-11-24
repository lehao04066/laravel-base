@extends('layouts.main')

@section('title', 'Ghép kèo cầu lông - GoSport')

@push('styles')
<style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 sm:py-10" x-data="{ createModal: @js($errors->any()) }">
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-bold">Chưa thể đăng kèo. Vui lòng kiểm tra lại:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="relative mb-7 overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950 via-teal-900 to-slate-900 p-6 text-white shadow-xl sm:p-9">
        <div class="absolute -right-12 -top-20 h-64 w-64 rounded-full bg-emerald-400/20 blur-3xl"></div>
        <div class="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-300/25 bg-emerald-300/10 px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest text-emerald-200">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-300"></span> Cộng đồng GoSport
                </span>
                <h1 class="mt-3 font-headline text-2xl font-extrabold sm:text-4xl">Ghép kèo & giao lưu cầu lông</h1>
                <p class="mt-2 max-w-xl text-xs leading-6 text-slate-300 sm:text-sm">Tìm người chơi cùng trình độ, chọn sân và thời gian phù hợp, rồi tham gia phòng giao lưu.</p>
            </div>

            @auth
                @if ($venues->isNotEmpty())
                    <button type="button" @click="createModal = true" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-400 px-5 py-3 text-sm font-extrabold text-slate-950 shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-300">
                        <span class="material-symbols-outlined">add_circle</span> Đăng kèo mới
                    </button>
                @else
                    <span class="rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-xs font-semibold text-slate-200">Chưa có sân đang hoạt động để đăng kèo.</span>
                @endif
            @else
                <a href="{{ route('login') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-400 px-5 py-3 text-sm font-extrabold text-slate-950 shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-300">
                    <span class="material-symbols-outlined">login</span> Đăng nhập để đăng kèo
                </a>
            @endauth
        </div>
    </section>

    <form method="GET" action="{{ route('matchmaking.index') }}" class="mb-7 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-[1.2fr_1.4fr_1fr_1fr_auto] lg:items-end">
        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Trình độ
            <select name="level" class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-emerald-500">
                <option value="all">Tất cả trình độ</option>
                @foreach ($levelLabels as $value => $label)
                    <option value="{{ $value }}" @selected(request('level') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Cụm sân
            <select name="venue_id" class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-emerald-500">
                <option value="">Tất cả cụm sân</option>
                @foreach ($venues as $venue)
                    <option value="{{ $venue->id }}" @selected((string) request('venue_id') === (string) $venue->id)>{{ $venue->name }}{{ $venue->district ? ' · '.$venue->district : '' }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Ngày chơi
            <input type="date" name="play_date" min="{{ now()->toDateString() }}" value="{{ request('play_date') }}" class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-emerald-500">
        </label>
        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Mức phí tối đa/người
            <input type="number" name="max_fee" min="0" step="10000" value="{{ request('max_fee') }}" placeholder="Không giới hạn" class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-emerald-500">
        </label>
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-slate-700">
            <span class="material-symbols-outlined text-base">filter_list</span> Lọc kèo
        </button>
    </form>

    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <h2 class="font-headline text-lg font-extrabold text-slate-900">Kèo sắp diễn ra</h2>
            <p class="mt-1 text-xs text-slate-500">{{ $matches->total() }} kèo phù hợp</p>
        </div>
        @if (request()->hasAny(['level', 'venue_id', 'play_date', 'max_fee']))
            <a href="{{ route('matchmaking.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Xóa bộ lọc</a>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($matches as $match)
            @php
                $joinedParticipants = $match->participants->where('status', 'joined');
                $playerCapacity = max(1, (int) $match->players_needed);
                $joinedCount = max((int) $match->players_joined, $joinedParticipants->count());
                $progress = min(100, ($joinedCount / $playerCapacity) * 100);
                $alreadyJoined = auth()->check() && $joinedParticipants->contains(fn ($participant) => (int) $participant->user_id === (int) auth()->id());
            @endphp
            <article class="flex flex-col rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        @if ($match->user?->avatar)
                            <img src="{{ $match->user->avatar }}" alt="{{ $match->user->name }}" class="h-11 w-11 shrink-0 rounded-full border-2 border-emerald-100 object-cover">
                        @else
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-emerald-100 text-sm font-extrabold text-emerald-800">{{ mb_strtoupper(mb_substr($match->user?->name ?? 'G', 0, 1)) }}</span>
                        @endif
                        <div class="min-w-0">
                            <p class="truncate text-xs font-extrabold text-slate-900">{{ $match->user?->name ?? 'Thành viên GoSport' }}</p>
                            <p class="mt-0.5 text-[10px] text-slate-400">Người tạo kèo</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-wide text-emerald-800">
                        {{ $levelLabels[$match->target_level] ?? 'Giao lưu' }}
                    </span>
                </div>

                <h3 class="mt-5 line-clamp-2 font-headline text-base font-extrabold text-slate-900">{{ $match->title }}</h3>
                <p class="mt-1 line-clamp-2 min-h-9 text-xs leading-5 text-slate-500">{{ $match->description ?: 'Kèo giao lưu cầu lông, chào đón người chơi phù hợp.' }}</p>

                <div class="mt-4 space-y-2 rounded-2xl border border-slate-100 bg-slate-50 p-3.5 text-xs">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="material-symbols-outlined text-base text-blue-600">location_on</span>
                        <span class="font-semibold">{{ $match->venue?->name ?? 'Cụm sân không còn hoạt động' }}{{ $match->venue?->district ? ' · '.$match->venue->district : '' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-700">
                        <span class="material-symbols-outlined text-base text-amber-500">schedule</span>
                        <span>{{ $match->play_datetime->format('d/m/Y · H:i') }} · {{ number_format((float) $match->duration_hours, 1) }} giờ</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-slate-200 pt-2">
                        <span class="text-[10px] font-semibold text-slate-500">Dự kiến mỗi người</span>
                        <strong class="font-headline text-sm text-emerald-700">{{ number_format((float) $match->fee_per_player, 0, ',', '.') }}đ</strong>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="mb-1.5 flex justify-between text-[10px] font-bold text-slate-600">
                        <span>Phòng giao lưu</span><span>{{ min($joinedCount, $playerCapacity) }}/{{ $playerCapacity }} người</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $progress }}%"></div></div>
                    <div class="mt-2 flex items-center gap-1.5">
                        @forelse ($joinedParticipants->take(5) as $participant)
                            <span class="grid h-7 w-7 place-items-center overflow-hidden rounded-full border-2 border-white bg-blue-100 text-[9px] font-extrabold text-blue-800" title="{{ $participant->user?->name ?? 'Thành viên' }}">
                                @if ($participant->user?->avatar)
                                    <img src="{{ $participant->user->avatar }}" alt="" class="h-full w-full object-cover">
                                @else
                                    {{ mb_strtoupper(mb_substr($participant->user?->name ?? 'G', 0, 1)) }}
                                @endif
                            </span>
                        @empty
                            <span class="text-[10px] text-slate-400">Chưa có thành viên tham gia.</span>
                        @endforelse
                        @if ($joinedCount > 5)
                            <span class="text-[10px] font-bold text-slate-500">+{{ $joinedCount - 5 }}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-auto pt-5">
                    @if (auth()->check() && (int) $match->user_id === (int) auth()->id())
                        <div class="rounded-xl bg-slate-100 py-2.5 text-center text-xs font-bold text-slate-500">Kèo do bạn tạo</div>
                    @elseif ($alreadyJoined)
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 py-2.5 text-center text-xs font-extrabold text-emerald-800">Bạn đã ở trong phòng này</div>
                    @elseif ($match->status === 'full' || $joinedCount >= $playerCapacity)
                        <button type="button" disabled class="w-full cursor-not-allowed rounded-xl bg-slate-100 py-2.5 text-xs font-bold text-slate-400">Phòng đã đủ người</button>
                    @elseif (! auth()->check())
                        <a href="{{ route('login') }}" class="block w-full rounded-xl bg-emerald-600 py-2.5 text-center text-xs font-extrabold text-white transition hover:bg-emerald-700">Đăng nhập để tham gia</a>
                    @else
                        <form method="POST" action="{{ route('matchmaking.join', $match->id) }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-emerald-700">
                                <span class="material-symbols-outlined text-base">group_add</span> Tham gia phòng
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
                <span class="material-symbols-outlined text-5xl text-slate-300">sports_tennis</span>
                <h3 class="mt-3 font-headline text-base font-extrabold text-slate-800">Chưa tìm thấy kèo phù hợp</h3>
                <p class="mt-1 text-xs text-slate-500">Thử đổi bộ lọc hoặc đăng kèo mới để tìm bạn chơi.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-7">{{ $matches->links() }}</div>
    <p class="mt-5 text-center text-[10px] text-slate-400">Mức phí là dự kiến do người đăng cung cấp; việc thanh toán được các thành viên tự thống nhất.</p>

    @auth
        <div x-show="createModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center overflow-y-auto bg-slate-950/70 p-4 backdrop-blur-sm" @keydown.escape.window="createModal = false">
            <div class="my-5 max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl sm:p-8" @click.outside="createModal = false">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-700">Tìm bạn cùng sân</span>
                        <h2 class="mt-1 font-headline text-xl font-extrabold text-slate-900">Đăng kèo mới</h2>
                        <p class="mt-1 text-xs text-slate-500">Chia sẻ trình độ, lịch chơi và mức phí dự kiến.</p>
                    </div>
                    <button type="button" @click="createModal = false" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Đóng">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('matchmaking.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <label class="block text-xs font-bold text-slate-700">Tiêu đề kèo
                        <input name="title" value="{{ old('title') }}" maxlength="120" required placeholder="Ví dụ: Tìm 2 bạn đánh đôi trình độ TB+" class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:bg-white">
                    </label>
                    <label class="block text-xs font-bold text-slate-700">Cụm sân
                        <select name="venue_id" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:bg-white">
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}" @selected((string) old('venue_id') === (string) $venue->id)>{{ $venue->name }}{{ $venue->district ? ' · '.$venue->district : '' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <label class="block text-xs font-bold text-slate-700">Ngày chơi
                            <input type="date" name="play_date" min="{{ now()->toDateString() }}" value="{{ old('play_date', now()->toDateString()) }}" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm outline-none focus:border-emerald-500">
                        </label>
                        <label class="block text-xs font-bold text-slate-700">Giờ bắt đầu
                            <input type="time" name="play_time" value="{{ old('play_time', '18:00') }}" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm outline-none focus:border-emerald-500">
                        </label>
                        <label class="block text-xs font-bold text-slate-700">Thời lượng
                            <select name="duration_hours" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm outline-none focus:border-emerald-500">
                                @foreach (['1' => '1 giờ', '1.5' => '1,5 giờ', '2' => '2 giờ', '2.5' => '2,5 giờ', '3' => '3 giờ', '4' => '4 giờ'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('duration_hours', '2') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <label class="block text-xs font-bold text-slate-700 sm:col-span-2">Trình độ mong muốn
                            <select name="target_level" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-500">
                                @foreach ($levelLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('target_level', 'intermediate') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="block text-xs font-bold text-slate-700">Tổng số người
                            <input type="number" name="players_needed" min="2" max="12" value="{{ old('players_needed', 4) }}" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm outline-none focus:border-emerald-500">
                        </label>
                    </div>
                    <label class="block text-xs font-bold text-slate-700">Mức phí dự kiến mỗi người (đ)
                        <input type="number" name="fee_per_player" min="0" max="2000000" step="5000" value="{{ old('fee_per_player', 60000) }}" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-500">
                    </label>
                    <label class="block text-xs font-bold text-slate-700">Mô tả thêm <span class="font-medium text-slate-400">(không bắt buộc)</span>
                        <textarea name="description" rows="3" maxlength="1000" placeholder="Trình độ, vị trí cần tuyển hoặc thông tin giao lưu..." class="mt-1.5 w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-500">{{ old('description') }}</textarea>
                    </label>
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-700">
                        <span class="material-symbols-outlined text-lg">campaign</span> Đăng kèo giao lưu
                    </button>
                </form>
            </div>
        </div>
    @endauth
</div>
@endsection
