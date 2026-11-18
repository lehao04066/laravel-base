<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GoSport - Nền Tảng Đặt Sân Cầu Lông Chuyên Nghiệp')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-headline { font-family: 'Space Grotesk', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-[#F8FAFC]" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navbar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                <!-- Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition">
                            <span class="material-symbols-outlined text-2xl">sports_tennis</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-2xl tracking-tight text-slate-900">Go<span class="text-blue-600">Sport</span></span>
                            <span class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase">Arena Booking</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-600">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-blue-600 bg-blue-50 font-bold hover:text-blue-600 transition">Khám Phá Sân</a>
                        <a href="javascript:void(0)" class="px-3 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50/50 transition text-slate-600">Ghép Kèo</a>
                        <a href="javascript:void(0)" class="px-3 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50/50 transition text-slate-600">Ưu Đãi &amp; Voucher</a>
                        <a href="javascript:void(0)" class="px-3 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50/50 transition text-slate-600">Academy HLV</a>
                        <a href="javascript:void(0)" class="px-3 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50/50 transition text-slate-600">Badminton Hub</a>
                    </nav>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        @if (auth()->user()->hasAnyRole(['super_admin', 'admin']))
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-500/20 transition">
                                <span class="material-symbols-outlined text-base">admin_panel_settings</span>
                                <span>Dashboard Admin</span>
                            </a>
                        @endif

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-slate-100 transition focus:outline-none" aria-label="Tài khoản">
                                <span class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white border-2 border-blue-600">
                                    <span class="material-symbols-outlined text-xl">person</span>
                                </span>
                                <span class="hidden lg:block text-sm font-semibold text-slate-800 pr-1">{{ auth()->user()->name }}</span>
                                <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-xs text-slate-400 font-medium">Tài khoản đang đăng nhập</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    @php
                                        $accountRole = match (true) {
                                            auth()->user()->hasAnyRole(['super_admin', 'admin']) => ['label' => 'Quản trị', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                                            auth()->user()->hasRole('owner') => ['label' => 'Chủ sân', 'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
                                            auth()->user()->hasRole('staff') => ['label' => 'Nhân viên', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                                            auth()->user()->hasRole('coach') => ['label' => 'Huấn luyện viên', 'class' => 'bg-violet-50 text-violet-700 border-violet-100'],
                                            auth()->user()->hasRole('customer') => ['label' => 'Thành viên', 'class' => 'bg-blue-50 text-blue-700 border-blue-100'],
                                            default => ['label' => 'Tài khoản', 'class' => 'bg-slate-50 text-slate-600 border-slate-200'],
                                        };
                                    @endphp
                                    <span class="mt-2 inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider {{ $accountRole['class'] }}">
                                        {{ $accountRole['label'] }}
                                    </span>
                                </div>

                                @if (auth()->user()->hasAnyRole(['super_admin', 'admin']))
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-semibold transition">
                                        <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                        <span>Trang quản trị</span>
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition text-left bg-transparent border-0 cursor-pointer">
                                            <span class="material-symbols-outlined text-lg">logout</span>
                                            <span>Đăng xuất</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 transition">
                            <span class="material-symbols-outlined text-lg">person_add</span>
                            <span>Đăng ký</span>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition">
                            <span class="material-symbols-outlined text-lg">login</span>
                            <span>Đăng nhập</span>
                        </a>
                    @endauth

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none" aria-label="Menu">
                        <span class="material-symbols-outlined text-2xl" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden py-4 border-t border-slate-200 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-blue-600 bg-blue-50">Khám Phá Sân</a>
                <a href="javascript:void(0)" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Ghép Kèo</a>
                <a href="javascript:void(0)" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Ưu Đãi &amp; Voucher</a>
                <a href="javascript:void(0)" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Academy HLV</a>
                <a href="javascript:void(0)" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Badminton Hub</a>
                <div class="pt-2 border-t border-slate-100">
                    @auth
                        @if (auth()->user()->hasAnyRole(['super_admin', 'admin']))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700">
                                <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                <span>Mở Dashboard Admin</span>
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 mb-2">
                            <span class="material-symbols-outlined text-lg">person_add</span>
                            <span>Đăng ký thành viên</span>
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-symbols-outlined text-lg">login</span>
                            <span>Đăng nhập</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">sports_tennis</span>
                    </div>
                    <span class="font-headline font-bold text-xl text-white">Go<span class="text-blue-500">Sport</span></span>
                </div>
                <p class="text-xs leading-relaxed text-slate-400">Hệ thống quản lý và đặt sân cầu lông số 1 Việt Nam. Tích hợp thanh toán an toàn, mở cửa barrier IoT thông minh và cộng đồng ghép kèo lông thủ đỉnh cao.</p>
                <div class="flex items-center gap-2 text-xs text-slate-300">
                    <span class="material-symbols-outlined text-sm text-blue-500">verified</span>
                    <span>Thanh toán VNPay &amp; VietQR an toàn</span>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-sm text-white mb-4">Cụm Sân GoSport</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="javascript:void(0)" class="hover:text-white transition">GoSport Arena Cầu Giấy (12 sân)</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition">GoSport Premium Mỹ Đình (8 sân)</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition">GoSport Star Thanh Xuân (6 sân)</a></li>
                    <li><span class="text-emerald-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">sensors</span> Kiosk Tự Check-in Barrier
                    </span></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-white mb-4">Dịch Vụ &amp; Tiện Ích</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="javascript:void(0)" class="hover:text-white transition">Sàn Ghép Kèo Đấu</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition">Học Viện GoSport Academy</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition">Gói Thẻ Hội Viên VIP</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition">Cẩm Nang Kỹ Thuật Badminton</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-white mb-4">Hỗ Trợ &amp; Liên Hệ</h4>
                <p class="text-xs mb-2"><span class="text-slate-300">Hotline:</span> 1900 6868 (06:00 - 23:00)</p>
                <p class="text-xs mb-2"><span class="text-slate-300">Email:</span> support@gosport.vn</p>
                <p class="text-xs"><span class="text-slate-300">Địa chỉ:</span> 26 Phạm Văn Đồng, Cầu Giấy, Hà Nội</p>
                <div class="mt-4 pt-4 border-t border-slate-800 text-[11px] text-slate-500">
                    &copy; 2026 GoSport Badminton System. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
