@extends('layouts.main')

@section('title', 'Ghép kèo - GoSport')

@push('styles')
<style>
    .match-hero { background: linear-gradient(118deg, #0f172a 0%, #172554 54%, #075985 100%); }
    .match-card { transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    .match-card:hover { transform: translateY(-3px); box-shadow: 0 18px 40px rgba(15, 23, 42, .11); border-color: #bfdbfe; }
    .match-input { width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; padding: .72rem .85rem; font-size: .82rem; color: #334155; outline: none; }
    .match-input:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
</style>
@endpush

@section('content')
<div class="bg-slate-50 min-h-screen">
    <section class="match-hero text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-11 sm:py-14">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wide text-blue-200"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> GOSPORT COMMUNITY</div>
                    <h1 class="font-headline text-3xl sm:text-5xl font-extrabold tracking-tight mt-3">Tìm người chơi cùng trình độ</h1>
                    <p class="text-blue-100/80 mt-4 text-sm sm:text-base leading-relaxed">Ghép kèo đánh đơn hoặc đánh đôi, chia sẻ tiền sân và gặp thêm những người yêu cầu lông.</p>
                </div>
                <div class="flex items-center gap-5 text-sm text-blue-100/80"><div><strong class="block text-2xl text-white">{{ $posts->total() }}</strong><span>Kèo đang mở</span></div><div class="w-px h-10 bg-white/20"></div><div><strong class="block text-2xl text-white">24/7</strong><span>Cộng đồng hoạt động</span></div></div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex flex-col gap-4 mb-6"><div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-widest text-blue-600">Matchmaking</p><h2 class="font-headline text-2xl font-extrabold text-slate-900 mt-1">Kèo dành cho bạn</h2></div><span class="text-xs text-slate-400">{{ $posts->total() }} kèo phù hợp</span></div><form method="GET" action="{{ route('matchmaking') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2"><input name="q" value="{{ request('q') }}" placeholder="Tìm tên sân hoặc kèo" class="match-input"><select name="level" class="match-input"><option value="all">Tất cả trình độ</option><option value="beginner" @selected(request('level') === 'beginner')>Mới chơi</option><option value="intermediate" @selected(request('level') === 'intermediate')>Trung bình</option><option value="intermediate_plus" @selected(request('level') === 'intermediate_plus')>Trung bình+</option><option value="advanced" @selected(request('level') === 'advanced')>Nâng cao</option></select><select name="venue_id" class="match-input"><option value="">Tất cả sân</option>@foreach($venues as $venue)<option value="{{ $venue->id }}" @selected((string) request('venue_id') === (string) $venue->id)>{{ $venue->name }}</option>@endforeach</select><input type="date" name="play_date" value="{{ request('play_date') }}" class="match-input"><input type="number" name="max_fee" min="0" value="{{ request('max_fee') }}" placeholder="Phí tối đa" class="match-input"><button class="sm:col-span-2 lg:col-span-5 justify-self-end px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-blue-700 transition">Tìm kiếm kèo</button></form></div>

        <div class="grid lg:grid-cols-[minmax(0,1fr)_340px] gap-7 items-start">
            <div class="space-y-4">
                @forelse($posts as $post)
                    <article class="match-card bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">
                        <div class="flex gap-4">
                            <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 items-center justify-center shrink-0"><span class="material-symbols-outlined text-2xl">sports_tennis</span></div>
                            <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-2"><span class="text-[10px] font-extrabold tracking-wide text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">ĐANG MỞ</span><span class="text-[10px] font-extrabold tracking-wide text-blue-600 bg-blue-50 px-2 py-1 rounded-md">{{ $post->match_type === 'singles' ? 'ĐÁNH ĐƠN' : 'ĐÁNH ĐÔI' }}</span><span class="text-xs text-slate-400">{{ $post->venue?->name ?? 'Chưa cập nhật sân' }}</span></div><h3 class="font-headline text-lg font-bold text-slate-900 mt-2 truncate"><a href="{{ route('matchmaking.show', $post) }}" class="hover:text-blue-600">{{ $post->title }}</a></h3><p class="text-sm text-slate-500 mt-1.5 line-clamp-2">{{ $post->description ?: 'Cùng giao lưu và rèn luyện trong một trận cầu vui vẻ.' }}</p></div>
                            <div class="hidden sm:block text-right shrink-0"><p class="text-[11px] text-slate-400">Người tạo</p><p class="text-sm font-semibold text-slate-700 mt-1">{{ $post->user?->name ?? 'Thành viên' }}</p></div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-y-4 gap-x-5 mt-5 pt-4 border-t border-slate-100"><div><p class="text-[11px] text-slate-400">Lịch chơi</p><p class="text-sm font-bold text-slate-700 mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-base text-blue-500">schedule</span>{{ $post->play_datetime->format('d/m H:i') }}</p></div><div><p class="text-[11px] text-slate-400">Trình độ BWF</p><p class="text-sm font-bold text-slate-700 mt-1">{{ str_replace('_', ' ', ucfirst($post->target_level)) }}</p></div><div><p class="text-[11px] text-slate-400">Chỗ trống</p><p class="text-sm font-bold text-slate-700 mt-1">{{ max(0, $post->players_needed - $post->players_joined) }} chỗ</p></div><div><p class="text-[11px] text-slate-400">Phí chia sẻ</p><p class="text-sm font-extrabold text-blue-600 mt-1">{{ number_format($post->fee_per_player, 0, ',', '.') }}đ</p></div></div>
                        <div class="flex items-center justify-between gap-3 mt-5"><span class="text-xs text-slate-400">{{ $post->players_joined }}/{{ $post->players_needed }} người đã tham gia</span>@if($post->user_id !== Auth::id())<form method="POST" action="{{ route('matchmaking.join', $post) }}">@csrf<button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition"><span class="material-symbols-outlined text-base">group_add</span>Tham gia phòng</button></form>@else<a href="{{ route('matchmaking.show', $post) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Mở phòng giao lưu →</a>@endif</div>
                    </article>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center"><span class="material-symbols-outlined text-5xl text-slate-300">search_off</span><h3 class="font-headline font-bold text-slate-800 mt-3">Chưa có kèo phù hợp</h3><p class="text-sm text-slate-500 mt-1">Hãy tạo kèo đầu tiên để tìm bạn chơi cùng.</p></div>
                @endforelse
                <div class="pt-2">{{ $posts->links() }}</div>
            </div>

            <aside class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:sticky lg:top-24"><div class="flex items-start gap-3 pb-5 border-b border-slate-100"><div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center"><span class="material-symbols-outlined">add</span></div><div><h2 class="font-headline text-xl font-extrabold text-slate-900">Tạo kèo mới</h2><p class="text-xs text-slate-500 mt-1">Chủ động tìm bạn chơi</p></div></div><form method="POST" action="{{ route('matchmaking.store') }}" class="space-y-3 pt-5">@csrf<input name="title" value="{{ old('title') }}" required placeholder="Tiêu đề kèo, ví dụ: Tìm bạn đánh tối nay" class="match-input"><select name="match_type" required class="match-input"><option value="doubles">Đánh đôi</option><option value="singles">Đánh đơn</option></select><select name="venue_id" required class="match-input"><option value="">Chọn cụm sân</option>@foreach($venues as $venue)<option value="{{ $venue->id }}">{{ $venue->name }}</option>@endforeach</select><input type="datetime-local" name="play_datetime" value="{{ old('play_datetime') }}" required class="match-input"><div class="grid grid-cols-2 gap-3"><input type="number" name="players_needed" min="2" max="20" value="{{ old('players_needed', 4) }}" required placeholder="Số người" class="match-input"><input type="number" name="fee_per_player" min="0" value="{{ old('fee_per_player', 0) }}" placeholder="Phí/người" class="match-input"></div><div class="grid grid-cols-2 gap-3"><select name="target_level" class="match-input"><option value="all">Mọi trình độ</option><option value="beginner">Mới chơi</option><option value="intermediate">Trung bình</option><option value="intermediate_plus">Trung bình+</option><option value="advanced">Nâng cao</option></select><select name="duration_hours" class="match-input"><option value="2">2 giờ</option><option value="1">1 giờ</option><option value="1.5">1,5 giờ</option><option value="3">3 giờ</option></select></div><textarea name="description" rows="3" placeholder="Mô tả thêm về kèo" class="match-input">{{ old('description') }}</textarea>@if($errors->any())<p class="text-xs text-rose-600">{{ $errors->first() }}</p>@endif<button class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-sm shadow-blue-500/20 transition">Đăng kèo ngay</button></form></aside>
        </div>
    </section>
</div>
@endsection
