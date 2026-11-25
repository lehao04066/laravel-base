<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GoSport Admin - Trung Tâm Quản Trị Vận Hành Sân')</title>

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
            <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Quản Trị Hệ Thống</div>
            
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                <span class="material-symbols-outlined text-lg">monitoring</span>
                <span>Báo Cáo &amp; Chỉ Số</span>
            </a>

            <!-- Venues & Courts Link -->
            <a href="{{ route('admin.venues.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.venues.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                <span class="material-symbols-outlined text-lg">stadium</span>
                <span>Cụm Sân &amp; Sân Con</span>
            </a>

            <!-- Pricing & Operating Hours Link -->
            <a href="{{ route('admin.pricing.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.pricing.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                <span class="material-symbols-outlined text-lg">payments</span>
                <span>Giờ Mở Cửa &amp; Bảng Giá</span>
            </a>

            <!-- Bookings Management Link -->
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.bookings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                <span class="material-symbols-outlined text-lg">confirmation_number</span>
                <span>Quản Lý Đơn Đặt Sân</span>
            </a>

            <a href="{{ route('admin.crm.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.crm.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                <span class="material-symbols-outlined text-lg">loyalty</span>
                <span>CRM &amp; Hội Viên</span>
            </a>

            <div class="pt-6 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Khác</div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition">
                <span class="material-symbols-outlined text-lg text-emerald-400">open_in_new</span>
                <span>Xem Trang Khách Hàng</span>
            </a>
        </div>

        <!-- Sidebar Bottom: Admin User Info & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shrink-0">
                        A
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@gosport.vn' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Đăng xuất" class="p-1.5 text-slate-400 hover:text-rose-400 transition">
                        <span class="material-symbols-outlined text-xl">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header Bar -->
        <header class="h-18 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 font-headline">
                    @yield('page_title', 'GoSport Quản Trị')
                </h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                    <span class="material-symbols-outlined text-base">visibility</span>
                    <span class="hidden sm:inline">Trang Chủ</span>
                </a>

                <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Main Body Area -->
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="p-4 mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-rose-600 text-xl">error</span>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm space-y-1">
                    <div class="font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">warning</span> Có lỗi xảy ra:
                    </div>
                    <ul class="list-disc pl-5 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
