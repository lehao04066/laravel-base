@extends('layouts.main')

@section('title', 'Khám Phá Cụm Sân Cầu Lông GoSport - Đặt Sân Trực Tuyến')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-blue-900 via-indigo-950 to-slate-900 text-white py-14 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Background grid decoration -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="max-w-7xl mx-auto relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>HỆ THỐNG ĐẶT SÂN CẦU LÔNG THÔNG MINH SỐ 1 VIỆT NAM</span>
        </div>

        <h1 class="font-headline font-extrabold text-3xl sm:text-5xl lg:text-6xl tracking-tight leading-tight max-w-4xl mx-auto mb-4">
            Tìm Sân Thi Đấu Chuẩn BWF <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-teal-300 to-emerald-400">Giữ Chỗ Trong 3 Giây</span>
        </h1>

        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-8 leading-relaxed">
            Hơn 26 sân đấu cao cấp tại Hà Nội với thảm chống chấn thương, đèn Lumileds chống lóa, hệ thống Kiosk tự động check-in mở barrier cửa sân.
        </p>

        <!-- Multi-Criteria Search & Filter Form (GET) -->
        <div class="max-w-5xl mx-auto bg-white rounded-3xl p-5 sm:p-6 shadow-2xl text-slate-800 text-left">
            <form method="GET" action="{{ route('explore') }}" id="filterForm">
                
                <!-- Row 1: Keyword, District, Status -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 mb-4">
                    
                    <!-- Keyword Input (Tên cụm sân hoặc địa chỉ) -->
                    <div class="md:col-span-5 relative">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Từ khóa tìm kiếm</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tên cụm sân hoặc địa chỉ..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- District Filter (Quận/Huyện từ DB) -->
                    <div class="md:col-span-4 relative">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Khu vực quận / huyện</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">location_on</span>
                            <select name="district" class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition appearance-none">
                                <option value="all">Tất cả quận/huyện (Hà Nội)</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>
                                        Quận {{ $d }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Status Filter (Trạng thái mở cửa) -->
                    <div class="md:col-span-3 relative">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Trạng thái mở cửa</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">schedule</span>
                            <select name="status" class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition appearance-none">
                                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>🟢 Đang mở</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>🔴 Đang đóng</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Price Range & Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end mb-4 pt-2 border-t border-slate-100">
                    
                    <!-- Min Price -->
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Giá tối thiểu (đ/giờ)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">payments</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" step="10000" placeholder="Từ (VD: 120000)" class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Max Price -->
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Giá tối đa (đ/giờ)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">payments</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" step="10000" placeholder="Đến (VD: 200000)" class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Action Buttons: Apply & Clear -->
                    <div class="md:col-span-6 flex items-center gap-2.5">
                        <button type="submit" class="flex-1 py-2.5 px-5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/25 transition transform active:scale-98 flex items-center justify-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-lg">filter_alt</span>
                            <span>Áp dụng bộ lọc</span>
                        </button>
                        
                        <a href="{{ route('explore') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm rounded-xl transition flex items-center justify-center gap-1.5 shrink-0" title="Đặt lại toàn bộ tiêu chí">
                            <span class="material-symbols-outlined text-base">restart_alt</span>
                            <span class="hidden sm:inline">Xóa bộ lọc</span>
                        </a>
                    </div>
                </div>

                <!-- Row 3: Multi-Amenities Selection (Checkboxes) -->
                <div class="pt-3 border-t border-slate-100">
                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Tiện ích yêu cầu (Cụm sân phải có đủ tất cả tiện ích đã tích):
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-2">
                        @foreach($amenities as $am)
                            @php
                                $isChecked = in_array((string)$am->id, (array) request('amenities', []));
                            @endphp
                            <label class="flex items-center gap-2 p-2 rounded-xl border transition cursor-pointer text-xs {{ $isChecked ? 'bg-blue-50/80 border-blue-400 text-blue-900 font-bold' : 'bg-slate-50/60 border-slate-200/80 text-slate-700 hover:bg-slate-100/70' }}">
                                <input type="checkbox" name="amenities[]" value="{{ $am->id }}" {{ $isChecked ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                                <span class="material-symbols-outlined text-base {{ $isChecked ? 'text-blue-600' : 'text-slate-400' }}">{{ $am->icon }}</span>
                                <span class="truncate">{{ $am->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </form>
        </div>

        <!-- Quick filter chips -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-6 text-xs font-semibold">
            <a href="{{ route('explore', ['district' => 'Cầu Giấy']) }}" class="px-3.5 py-1.5 rounded-full {{ request('district') == 'Cầu Giấy' ? 'bg-blue-600 text-white' : 'bg-white/10 hover:bg-white/20 text-white' }} backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-emerald-400">near_me</span>
                <span>Quận Cầu Giấy</span>
            </a>
            <a href="{{ route('explore', ['status' => 'open']) }}" class="px-3.5 py-1.5 rounded-full {{ request('status') == 'open' ? 'bg-emerald-600 text-white' : 'bg-white/10 hover:bg-white/20 text-white' }} backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-emerald-300">schedule</span>
                <span>Sân đang mở cửa</span>
            </a>
            <a href="{{ route('explore', ['max_price' => '150000']) }}" class="px-3.5 py-1.5 rounded-full {{ request('max_price') == '150000' ? 'bg-blue-600 text-white' : 'bg-white/10 hover:bg-white/20 text-white' }} backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-amber-300">payments</span>
                <span>Giá dưới 150.000đ/h</span>
            </a>
            <a href="{{ route('explore', ['q' => 'điều hòa']) }}" class="px-3.5 py-1.5 rounded-full {{ request('q') == 'điều hòa' ? 'bg-blue-600 text-white' : 'bg-white/10 hover:bg-white/20 text-white' }} backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-blue-300">ac_unit</span>
                <span>Sân điều hòa</span>
            </a>
        </div>

    </div>
</section>

<!-- Server Validation Error Alert in Vietnamese -->
@if($errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-start gap-3 shadow-xs">
            <span class="material-symbols-outlined text-rose-600 mt-0.5">error</span>
            <div>
                <h4 class="font-bold text-sm">Thông báo lỗi bộ lọc dữ liệu:</h4>
                <ul class="list-disc list-inside text-xs mt-1 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Promotional Banner -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 relative z-20">
    <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white rounded-2xl p-4 sm:p-5 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">bolt</span>
            </div>
            <div>
                <span class="font-bold text-sm sm:text-base block uppercase tracking-wide">Flash Sale Giờ Vàng Chiều: Giảm ngay 20%</span>
                <p class="text-xs text-white/90">Áp dụng cho các khung giờ 14:00 - 17:00 tất cả các cụm sân hôm nay!</p>
            </div>
        </div>
        <a href="#venue-list" class="px-5 py-2.5 bg-white text-orange-600 font-bold text-xs sm:text-sm rounded-xl hover:bg-orange-50 shadow-md transition shrink-0">
            Xem Sân Đang Trống
        </a>
    </div>
</div>

<!-- Main Venue Explorer Grid -->
<section id="venue-list" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Results Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Hệ Thống Cơ Sở</span>
                @if(request()->anyFilled(['q', 'district', 'min_price', 'max_price', 'status', 'amenities']))
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Đang lọc kết quả</span>
                @endif
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-headline">Cụm Sân Cầu Lông Nổi Bật</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Được trang bị sàn thảm Yonex BWF, hệ thống đèn chống lóa và máy lạnh toàn diện.</p>
        </div>

        <div class="flex items-center gap-2">
            <div class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                Tìm thấy <span class="text-blue-600 font-extrabold">{{ $venues->total() }}</span> cụm sân
            </div>
            @if(request()->anyFilled(['q', 'district', 'min_price', 'max_price', 'status', 'amenities']))
                <a href="{{ route('explore') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 px-2.5 py-1.5 rounded-xl border border-rose-200 flex items-center gap-1 transition">
                    <span class="material-symbols-outlined text-sm">close</span>
                    <span>Xóa lọc</span>
                </a>
            @endif
        </div>
    </div>

    @if($venues->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/90 shadow-sm max-w-xl mx-auto my-8">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400">
                <span class="material-symbols-outlined text-3xl">search_off</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Không tìm thấy cụm sân phù hợp</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
                Không có cụm sân nào thỏa mãn tất cả các tiêu chí lọc hiện tại. Vui lòng thử nới lỏng khoảng giá, bỏ bớt tiện ích hoặc xóa bộ lọc để xem toàn bộ danh sách sân.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ route('explore') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">restart_alt</span>
                    <span>Xóa bộ lọc &amp; Xem tất cả</span>
                </a>
            </div>
        </div>
    @else
        <!-- Venues Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($venues as $venue)
                @php
                    $isOpen = $venue->isOpen();
                    $minPrice = $venue->pricingRules->min('price_per_hour') ?? 120000;
                    $detailUrl = Route::has('venues.show') ? route('venues.show', $venue->slug) : 'javascript:void(0)';
                @endphp
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col">
                    
                    <!-- Thumbnail with tags -->
                    <div class="relative aspect-video overflow-hidden bg-slate-900">
                        <img src="{{ $venue->featured_image }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                        
                        <!-- Badges: Open Status & Court count -->
                        <div class="absolute top-3 left-3 flex items-center gap-1.5 flex-wrap">
                            @if($isOpen)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    <span>Đang mở</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-600 text-white flex items-center gap-1 shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    <span>Đang đóng</span>
                                </span>
                            @endif

                            @if($venue->courts->count() >= 10)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500 text-white shadow-md">
                                    Đại Đấu Trường ({{ $venue->courts->count() }} sân)
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-500 text-white shadow-md">
                                    {{ $venue->courts->count() }} sân
                                </span>
                            @endif
                        </div>

                        <!-- Rating Badge -->
                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                            <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                            <span>{{ number_format($venue->rating_avg, 1) }}</span>
                            <span class="text-slate-400 font-normal">({{ $venue->reviews_count }})</span>
                        </div>

                        <!-- Distance / Area & Operating Hours -->
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
                                <a href="{{ $detailUrl }}">{{ $venue->name }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $venue->description }}</p>

                            <!-- Amenities tags -->
                            <div class="flex flex-wrap gap-1.5 mt-4">
                                @foreach($venue->amenities->take(3) as $am)
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs text-slate-500">{{ $am->icon }}</span>
                                        <span class="truncate max-w-[120px]">{{ $am->name }}</span>
                                    </span>
                                @endforeach
                                @if($venue->amenities->count() > 3)
                                    <span class="px-2 py-1 bg-slate-50 text-slate-400 text-[10px] font-bold rounded-lg border border-slate-200">
                                        +{{ $venue->amenities->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Pricing & CTA -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-lg font-extrabold text-blue-600 font-headline">
                                        {{ number_format($minPrice, 0, ',', '.') }}đ
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                                </div>
                            </div>

                            <a href="{{ $detailUrl }}" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5">
                                <span>Chọn Giờ Sân</span>
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Server-side Pagination Links with Query String -->
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 pt-6">
            <div class="text-xs text-slate-500">
                Hiển thị từ <span class="font-bold text-slate-800">{{ $venues->firstItem() ?? 0 }}</span> đến <span class="font-bold text-slate-800">{{ $venues->lastItem() ?? 0 }}</span> trong tổng số <span class="font-bold text-slate-800">{{ $venues->total() }}</span> cụm sân
            </div>

            <div>
                {{ $venues->links() }}
            </div>
        </div>
    @endif
</section>

<!-- Community Matchmaking Teaser -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">
    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-10 text-white relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 relative z-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-1 mb-2">
                    <span class="material-symbols-outlined text-base">groups</span>
                    Cộng Đồng Ghép Kèo GoSport
                </span>
                <h3 class="font-headline font-extrabold text-2xl sm:text-3xl text-white">Chưa Có Bạn Đánh Cầu Cùng?</h3>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-xl leading-relaxed">
                    Tham gia hàng trăm bài đăng xé vé ghép kèo mỗi ngày theo đúng trình độ (TB-, TB, TB+, Khá) tại Cầu Giấy, Mỹ Đình, Thanh Xuân.
                </p>
            </div>
            <a href="javascript:void(0)" class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition transform active:scale-98 shrink-0 flex items-center gap-2">
                <span>Xem Sàn Ghép Kèo Ngay</span>
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
@endsection
