<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GoSport Admin - Trung Tâm Quản Trị Vận Hành Đấu Trường')</title>

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
<body class="min-h-full flex bg-[#F8FAFC]" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
         style="display: none;"></div>

    <!-- Admin Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col border-r border-slate-800 min-h-screen fixed lg:static inset-y-0 left-0 z-50 transition-transform duration-300 ease-in-out">
        
        <!-- Logo -->
        <div class="h-18 flex items-center justify-between px-6 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                    <span class="material-symbols-outlined text-xl">sports_tennis</span>
                </div>
                <div>
                    <span class="font-headline font-bold text-xl text-white">Go<span class="text-blue-500">Sport</span></span>
                    <span class="block text-[10px] font-bold text-blue-400 tracking-wider uppercase">ADMIN PORTAL</span>
                </div>
            </div>

            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Vận Hành Sân</div>
            
            <!-- Active Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition bg-blue-600 text-white shadow-md shadow-blue-500/25">
                <span class="material-symbols-outlined text-lg">monitoring</span>
                <span>Báo Cáo &amp; Chỉ Số</span>
            </a>

            <!-- Disabled Sidebar Items outside scope -->
            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">calendar_view_week</span>
                    <span>Live Timeline Sân</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">payments</span>
                    <span>Cấu Hình Bảng Giá</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">stadium</span>
                    <span>Cấu Hình Smart Arena</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">sensors</span>
                    <span>Hệ Thống Thiết Bị IoT</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Khách Hàng &amp; CRM</div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">contacts</span>
                    <span>CRM &amp; Lịch Cố Định</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">rate_review</span>
                    <span>Đánh Giá Khách Hàng</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Kho &amp; Nhân Sự</div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">inventory_2</span>
                    <span>Kho &amp; Căng Vợt</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">badge</span>
                    <span>Nhân Sự &amp; Phân Ca</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg text-slate-600">emoji_events</span>
                    <span>Quản Lý Giải Đấu</span>
                </div>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-500 uppercase">Sắp mở</span>
            </div>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Liên Kết Ngoài</div>

            <!-- Working link to Home -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-blue-400 hover:bg-slate-800 hover:text-blue-300 transition">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                <span>Về Website Khách Hàng</span>
            </a>

            <div class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <span class="material-symbols-outlined text-lg text-slate-600">desktop_windows</span>
                <span>Bàn Làm Việc Lễ Tân</span>
            </div>

            <div class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                <span class="material-symbols-outlined text-lg text-slate-600">point_of_sale</span>
                <span>Thu Ngân POS</span>
            </div>
        </div>

        <!-- Current Admin Info -->
        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                    QT
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">Quản trị viên</p>
                    <p class="text-[10px] text-slate-400 truncate">admin@gosport.vn</p>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                Online
            </span>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Admin Topbar -->
        <header class="h-18 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <!-- Mobile Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none" aria-label="Toggle sidebar">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900 font-headline">@yield('page_title', 'Bảng Điều Khiển Quản Trị')</h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 hidden sm:block">Hệ Thống Quản Lý Đấu Trường Cầu Lông GoSport</p>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-xl text-xs font-semibold text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Cụm Sân: GoSport Arena Cầu Giấy (12 sân)</span>
                </div>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    <span class="material-symbols-outlined text-sm">home</span>
                    <span class="hidden sm:inline">Trang Chủ</span>
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-4 sm:p-8">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
