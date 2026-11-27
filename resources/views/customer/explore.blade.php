@extends('layouts.main')

@section('title', 'Khám Phá Cụm Sân Cầu Lông GoSport - Đặt Sân Trực Tuyến')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-blue-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="max-w-7xl mx-auto relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>HỆ THỐNG ĐẶT SÂN CẦU LÔNG GOSPORT</span>
        </div>

        <h1 class="font-headline font-extrabold text-3xl sm:text-5xl lg:text-6xl tracking-tight leading-tight max-w-4xl mx-auto mb-6">
            Tìm Sân Thi Đấu Chuẩn BWF <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-teal-300 to-emerald-400">Giữ Chỗ Nhanh Chóng</span>
        </h1>

        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-10 leading-relaxed">
            Hệ thống sân cầu lông cao cấp với thảm tiêu chuẩn BWF, đèn chống lóa, xem lịch trống trực quan và xác nhận đặt sân thanh toán tại quầy.
        </p>

        <!-- Search Box -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl sm:rounded-3xl p-3 sm:p-4 shadow-2xl text-slate-800">
            <form method="GET" action="{{ route('explore') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Keyword Input -->
                <div class="sm:col-span-5 relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm theo tên sân, đường, quận..." class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>

                <!-- District Filter -->
                <div class="sm:col-span-4 relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">location_on</span>
                    <select name="district" class="w-full pl-10 pr-8 py-3 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition appearance-none">
                        <option value="all">Tất cả khu vực (Hà Nội)</option>
                        @foreach($districts as $d)
                            <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">expand_more</span>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-3">
                    <button type="submit" class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/25 transition transform active:scale-98 flex items-center justify-center gap-2">
                        <span>Tìm Kiếm Sân</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Main Venue Explorer Grid -->
<section id="venue-list" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Hệ Thống Cơ Sở</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-headline">Cụm Sân Cầu Lông Đang Mở</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Được trang bị sàn thảm Yonex BWF, hệ thống đèn chống lóa và điều hòa mát mẻ.</p>
        </div>
        <div class="text-xs font-bold text-slate-400">
            Hiển thị {{ $venues->count() }} cơ sở
        </div>
    </div>

    @if($venues->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">search_off</span>
            <h3 class="text-lg font-bold text-slate-800">Không tìm thấy cụm sân phù hợp</h3>
            <p class="text-xs text-slate-500 mt-1">Vui lòng thử tìm kiếm với từ khóa hoặc khu vực khác.</p>
            <a href="{{ route('explore') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl">Xem tất cả cụm sân</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($venues as $venue)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col">
                    
                    <!-- Thumbnail with tags -->
                    <div class="relative aspect-video overflow-hidden bg-slate-900">
                        <img src="{{ $venue->featured_image ?? 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80' }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                <span>{{ $venue->courts->where('is_active', true)->count() }} Sân Hoạt Động</span>
                            </span>
                        </div>

                        <!-- Distance / Area -->
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                            <div class="flex items-center gap-1 truncate font-medium">
                                <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                                <span class="truncate">{{ $venue->district }}, {{ $venue->city }}</span>
                            </div>
                            <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                                {{ substr($venue->opening_time, 0, 5) }} - {{ substr($venue->closing_time, 0, 5) }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                                <a href="{{ route('venues.show', $venue->slug) }}">{{ $venue->name }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $venue->description ?? $venue->address }}</p>
                            <p class="text-[11px] text-slate-400 mt-2 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">pin_drop</span>
                                <span>{{ $venue->address }}</span>
                            </p>
                        </div>

                        <!-- Pricing & CTA -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-lg font-extrabold text-blue-600 font-headline">
                                        {{ number_format($venue->pricingRules->min('price_per_hour') ?? 120000, 0, ',', '.') }}đ
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                                </div>
                            </div>

                            <a href="{{ route('venues.show', $venue->slug) }}" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5">
                                <span>Chọn Giờ Sân</span>
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
