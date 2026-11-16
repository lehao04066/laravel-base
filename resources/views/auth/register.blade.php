@extends('layouts.main')

@section('title', 'Đăng ký thành viên | GoSport')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-100">
        <div class="text-center max-w-md mx-auto mb-8">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Thành viên mới</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1 font-headline">Gia nhập cộng đồng GoSport</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Tạo tài khoản để khám phá sân, kết nối với người chơi và lưu hồ sơ cầu lông của bạn.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-medium" role="alert">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Họ và tên *</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" maxlength="255" placeholder="Nguyễn Văn An" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Số điện thoại *</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" inputmode="tel" placeholder="0912 345 678 hoặc +84 912 345 678" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Địa chỉ email *</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" maxlength="255" placeholder="email@example.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mật khẩu *</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Ít nhất 8 ký tự" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Xác nhận mật khẩu *</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Nhập lại mật khẩu" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-base">sports_tennis</span>
                    <span>Hồ sơ vận động viên</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="skill_level" class="block text-xs font-semibold text-slate-600 mb-1">Trình độ thi đấu</label>
                        <select id="skill_level" name="skill_level" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                            <option value="beginner" @selected(old('skill_level', 'intermediate') === 'beginner')>Mới chơi</option>
                            <option value="intermediate_minus" @selected(old('skill_level', 'intermediate') === 'intermediate_minus')>Trung bình yếu (TB-)</option>
                            <option value="intermediate" @selected(old('skill_level', 'intermediate') === 'intermediate')>Trung bình (TB)</option>
                            <option value="intermediate_plus" @selected(old('skill_level', 'intermediate') === 'intermediate_plus')>Trung bình khá (TB+)</option>
                            <option value="advanced" @selected(old('skill_level', 'intermediate') === 'advanced')>Khá / bán chuyên</option>
                        </select>
                    </div>
                    <div>
                        <label for="play_style" class="block text-xs font-semibold text-slate-600 mb-1">Phong cách chơi</label>
                        <select id="play_style" name="play_style" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                            <option value="Tấn công Smash" @selected(old('play_style', 'Tấn công Smash') === 'Tấn công Smash')>Tấn công Smash</option>
                            <option value="Công thủ toàn diện" @selected(old('play_style') === 'Công thủ toàn diện')>Công thủ toàn diện</option>
                            <option value="Điều cầu phản tạt" @selected(old('play_style') === 'Điều cầu phản tạt')>Điều cầu phản tạt</option>
                            <option value="Phòng thủ phản công" @selected(old('play_style') === 'Phòng thủ phản công')>Phòng thủ phản công</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base rounded-xl shadow-lg shadow-blue-500/25 transition flex items-center justify-center gap-2">
                <span>Hoàn tất đăng ký</span>
                <span class="material-symbols-outlined text-xl">arrow_forward</span>
            </button>

            <p class="text-center text-xs text-slate-500">
                Đã có tài khoản GoSport?
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập tại đây</a>
            </p>
        </form>
    </div>
</div>
@endsection
