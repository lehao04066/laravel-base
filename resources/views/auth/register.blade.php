@extends('layouts.main')

@section('title', 'Đăng Ký Tài Khoản Khách Hàng - GoSport Arena')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-100">
        
        <div class="text-center max-w-md mx-auto mb-8">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Thành viên mới</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1 font-headline">Đăng Ký Tài Khoản</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Đăng ký tài khoản để xem lịch và đặt sân nhanh chóng tại hệ thống cụm sân GoSport.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Họ và tên *</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">badge</span>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Nguyễn Văn An" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Số điện thoại *</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">call</span>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0912345678" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Địa chỉ Email *</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">mail</span>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mật khẩu *</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                        <input type="password" name="password" required placeholder="Tối thiểu 6 ký tự" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Xác nhận mật khẩu *</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock_reset</span>
                        <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/25 transition transform active:scale-98 flex items-center justify-center gap-2">
                <span>Tạo Tài Khoản Khách Hàng</span>
                <span class="material-symbols-outlined text-lg">check_circle</span>
            </button>

            <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-500">
                Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập tại đây</a>
            </div>
        </form>
    </div>
</div>
@endsection
