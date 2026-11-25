@extends('layouts.main')

@section('title', 'Đăng Nhập & Đăng Ký Tài Khoản - GoSport Arena')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- Left Column: Hero Branding Showcase -->
        <section class="lg:col-span-7 rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden flex flex-col justify-between shadow-xl min-h-[580px] bg-gradient-to-br from-blue-900 via-indigo-950 to-slate-900">
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-semibold mb-6">
                    <span class="material-symbols-outlined text-sm">verified</span>
                    <span>Hệ Thống Đặt Sân Cầu Lông GoSport</span>
                </div>

                <h1 class="font-headline font-extrabold text-3xl sm:text-4xl lg:text-5xl leading-tight mb-4 tracking-tight">
                    Cháy Hết Mình <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-teal-300 to-emerald-400">Trên Từng Cú Smash</span>
                </h1>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-lg mb-8">
                    Tìm kiếm cụm sân dễ dàng, kiểm tra lịch trống trực quan và xác nhận đặt sân nhanh chóng với hình thức thanh toán trực tiếp tại sân.
                </p>

                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center gap-4 max-w-md">
                    <div class="w-12 h-12 rounded-xl bg-amber-400/20 border border-amber-300/30 flex items-center justify-center text-amber-300">
                        <span class="material-symbols-outlined text-2xl">sports_tennis</span>
                    </div>
                    <div>
                        <span class="font-bold text-white text-sm block">Đặt Sân Dễ Dàng &amp; Xác Nhận Ngay</span>
                        <p class="text-xs text-slate-300 mt-0.5">Thanh toán tại quầy khi đến nhận sân, kiểm soát lịch đặt minh bạch.</p>
                    </div>
                </div>
            </div>

            <!-- Reviewer Quote Card -->
            <div class="relative z-10 mt-8 p-4 rounded-2xl bg-slate-800/60 backdrop-blur-md border border-slate-700/50 flex items-center gap-4">
                <img class="w-12 h-12 rounded-full object-cover shrink-0 border border-blue-400/30 shadow-md" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Hoàng Nam">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1 text-amber-400">
                        <span class="material-symbols-outlined text-base">star</span>
                        <span class="material-symbols-outlined text-base">star</span>
                        <span class="material-symbols-outlined text-base">star</span>
                        <span class="material-symbols-outlined text-base">star</span>
                        <span class="material-symbols-outlined text-base">star</span>
                        <span class="text-xs font-bold text-white ml-1">4.9 / 5.0</span>
                    </div>
                    <p class="text-xs text-slate-200 mt-1 italic line-clamp-1">
                        "Đặt sân cực nhanh, không lo trùng giờ. Lịch sân hiển thị trực quan và chuẩn xác!"
                    </p>
                    <span class="text-[11px] font-semibold text-blue-300 mt-0.5 block">Hoàng Nam — Trưởng CLB Cầu Giấy Smash</span>
                </div>
            </div>

            <!-- Bottom Stats -->
            <div class="relative z-10 pt-6 border-t border-slate-800/80 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold text-slate-300">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-400 text-lg">stadium</span>
                    <span>Sân Thi Đấu Tiêu Chuẩn</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-400 text-lg">verified</span>
                    <span>Thảm BWF Chuyên Nghiệp</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400 text-lg">payments</span>
                    <span>Thanh Toán Tại Sân</span>
                </div>
            </div>
        </section>

        <!-- Right Column: Auth Card -->
        <section class="lg:col-span-5 flex flex-col justify-center" x-data="{ tab: 'login' }">
            <div class="w-full bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 flex flex-col">
                
                <!-- Tab Navigation: Login vs Register -->
                <div class="flex items-center p-1 rounded-2xl bg-slate-100 mb-6">
                    <button @click="tab = 'login'" :class="tab === 'login' ? 'bg-white text-blue-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'" class="flex-1 py-2.5 px-4 rounded-xl text-sm transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">login</span>
                        <span>Đăng nhập</span>
                    </button>
                    <button @click="tab = 'register'" :class="tab === 'register' ? 'bg-white text-blue-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'" class="flex-1 py-2.5 px-4 rounded-xl text-sm transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">person_add</span>
                        <span>Đăng ký mới</span>
                    </button>
                </div>

                <!-- LOGIN TAB -->
                <div x-show="tab === 'login'">
                    <div class="mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Thành viên GoSport</span>
                        <h2 class="text-2xl font-bold text-slate-900 mt-1 font-headline">Chào mừng trở lại!</h2>
                        <p class="text-xs text-slate-500 mt-1">Đăng nhập để giữ chỗ sân yêu thích và quản lý các lượt đặt sân.</p>
                    </div>

                    @if(session('error'))
                        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-medium">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email hoặc Số điện thoại</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">mail</span>
                                <input type="text" name="email" value="{{ old('email', 'customer@gosport.vn') }}" required placeholder="customer@gosport.vn hoặc 0988889999" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mật khẩu</label>
                                <span class="text-xs font-semibold text-slate-400">Mật khẩu demo: password123</span>
                            </div>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                                <input type="password" name="password" value="password123" required placeholder="Nhập mật khẩu" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                                <span class="text-xs font-medium text-slate-600">Ghi nhớ đăng nhập</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/25 transition transform active:scale-98 flex items-center justify-center gap-2">
                            <span>Đăng Nhập Ngay</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </form>

                    <!-- Quick Demo Role Logins -->
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Đăng nhập nhanh theo vai trò (Demo):</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <button type="button" onclick="fillRole('customer@gosport.vn', 'password123')" class="p-2.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-left transition truncate">
                                🏸 Khách Hàng (Customer)
                            </button>
                            <button type="button" onclick="fillRole('admin@gosport.vn', 'password123')" class="p-2.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-left transition truncate">
                                ⚙️ Quản Trị Viên (Admin)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- REGISTER TAB -->
                <div x-show="tab === 'register'">
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-emerald-600">
                            <span class="material-symbols-outlined text-sm">how_to_reg</span>
                            Tài khoản khách hàng
                        </span>
                        <h2 class="text-2xl font-bold text-slate-900 mt-1 font-headline">Đăng ký tài khoản</h2>
                        <p class="text-xs text-slate-500 mt-1">Đăng ký tài khoản để tìm và đặt sân dễ dàng tại các cụm sân GoSport.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Họ và Tên *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">badge</span>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Nguyễn Văn An" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Số điện thoại *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">call</span>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0912345678" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">mail</span>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mật khẩu *</label>
                                <input type="password" name="password" required placeholder="Tối thiểu 6 ký tự" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Xác nhận *</label>
                                <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/25 transition transform active:scale-98 flex items-center justify-center gap-2">
                            <span>Tạo Tài Khoản Khách Hàng</span>
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                        </button>
                    </form>
                </div>

            </div>
        </section>

    </div>
</div>

<script>
    function fillRole(email, password) {
        document.querySelector('input[name="email"]').value = email;
        document.querySelector('input[name="password"]').value = password;
    }
</script>
@endsection
