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
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('home', 'explore', 'venues.*') ? 'text-blue-600 bg-blue-50 font-bold' : 'hover:text-blue-600 hover:bg-blue-50/50' }} transition">Khám Phá Sân</a>
                        @auth
                            <a href="{{ route('my-bookings') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('my-bookings*') ? 'text-blue-600 bg-blue-50 font-bold' : 'hover:text-blue-600 hover:bg-blue-50/50' }} transition">Đơn Đặt Của Tôi</a>
                        @endauth
                    </nav>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-500/20 transition">
                                <span class="material-symbols-outlined text-base">admin_panel_settings</span>
                                <span>Khu Vực Quản Trị</span>
                            </a>
                        @endif

                        <!-- User Profile Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-slate-100 transition focus:outline-none">
                                <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm border-2 border-blue-600">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden lg:block text-sm font-semibold text-slate-800 pr-1">{{ Auth::user()->name }}</span>
                                <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-xs text-slate-400 font-medium">Đăng nhập với tư cách</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[11px] font-semibold rounded-full {{ Auth::user()->isAdmin() ? 'bg-indigo-50 text-indigo-700' : 'bg-blue-50 text-blue-700' }}">
                                        {{ Auth::user()->isAdmin() ? 'Quản Trị Viên (Admin)' : 'Khách Hàng (Client)' }}
                                    </span>
                                </div>

                                <div class="py-1 text-sm font-medium text-slate-700">
                                    <a href="{{ route('my-bookings') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 transition">
                                        <span class="material-symbols-outlined text-slate-400 text-lg">confirmation_number</span>
                                        <span>Đơn đặt của tôi</span>
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-indigo-600 hover:bg-indigo-50 font-semibold transition">
                                            <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                            <span>Trang Quản Trị Admin &rarr;</span>
                                        </a>
                                    @endif
                                </div>

                                <div class="border-t border-slate-100 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 transition text-left">
                                            <span class="material-symbols-outlined text-lg">logout</span>
                                            <span>Đăng xuất</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition">
                            Đăng ký
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
                @auth
                    <a href="{{ route('my-bookings') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Đơn Đặt Của Tôi</a>
                    @if(Auth::user()->isAdmin())
                        <div class="pt-2 border-t border-slate-100">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700">
                                <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                <span>Khu Vực Quản Trị</span>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="pt-2 border-t border-slate-100 flex gap-2">
                        <a href="{{ route('login') }}" class="flex-1 text-center py-2 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center py-2 rounded-xl text-sm font-bold text-white bg-blue-600">Đăng ký</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-rose-600 text-xl">error</span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">sports_tennis</span>
                    </div>
                    <span class="font-headline font-bold text-xl text-white">Go<span class="text-blue-500">Sport</span></span>
                </div>
                <p class="text-xs leading-relaxed text-slate-400">Hệ thống quản lý và đặt sân cầu lông chuyên nghiệp. Tìm kiếm sân nhanh chóng, kiểm tra lịch trống chính xác và xác nhận đặt sân tiện lợi.</p>
                <div class="flex items-center gap-2 text-xs text-slate-300">
                    <span class="material-symbols-outlined text-sm text-blue-500">payments</span>
                    <span>Thanh toán trực tiếp tại sân</span>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-sm text-white mb-4">Liên Kết Nhanh</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Khám Phá Cụm Sân</a></li>
                    @auth
                        <li><a href="{{ route('my-bookings') }}" class="hover:text-white transition">Lịch Đặt Của Tôi</a></li>
                        @if(Auth::user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition text-indigo-400">Trang Quản Trị Admin</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Đăng Nhập Khách Hàng</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Đăng Ký Tài Khoản</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-white mb-4">Hỗ Trợ &amp; Liên Hệ</h4>
                <p class="text-xs mb-2"><span class="text-slate-300">Hotline:</span> 1900 6868 (06:00 - 23:00)</p>
                <p class="text-xs mb-2"><span class="text-slate-300">Email:</span> support@gosport.vn</p>
                <p class="text-xs"><span class="text-slate-300">Địa chỉ:</span> 26 Phạm Văn Đồng, Cầu Giấy, Hà Nội</p>
                <div class="mt-4 pt-4 border-t border-slate-800 text-[11px] text-slate-500">
                    &copy; {{ date('Y') }} GoSport Badminton System. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
