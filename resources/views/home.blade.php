@extends('layouts.main')

@section('title', 'Khám Phá Cụm Sân Cầu Lông GoSport - Đặt Sân Trực Tuyến')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-blue-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Background grid decoration -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="max-w-7xl mx-auto relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>HỆ THỐNG ĐẶT SÂN CẦU LÔNG THÔNG MINH SỐ 1 VIỆT NAM</span>
        </div>

        <h1 class="font-headline font-extrabold text-3xl sm:text-5xl lg:text-6xl tracking-tight leading-tight max-w-4xl mx-auto mb-6">
            Tìm Sân Thi Đấu Chuẩn BWF <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-teal-300 to-emerald-400">Giữ Chỗ Trong 3 Giây</span>
        </h1>

        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-10 leading-relaxed">
            Hơn 26 sân đấu cao cấp tại Hà Nội với thảm chống chấn thương, đèn Lumileds chống lóa, hệ thống Kiosk tự động check-in mở barrier cửa sân.
        </p>

        <!-- Search Box (Static UI) -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl sm:rounded-3xl p-3 sm:p-4 shadow-2xl text-slate-800">
            <form onsubmit="return false;" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                
                <!-- Keyword Input -->
                <div class="sm:col-span-5 relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input type="text" name="q" placeholder="Tìm theo tên sân, đường, quận..." class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>

                <!-- District Filter -->
                <div class="sm:col-span-4 relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">location_on</span>
                    <select name="district" class="w-full pl-10 pr-8 py-3 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition appearance-none">
                        <option value="all">Tất cả khu vực (Hà Nội)</option>
                        <option value="Cầu Giấy">Quận Cầu Giấy (12 sân)</option>
                        <option value="Nam Từ Liêm">Quận Nam Từ Liêm (Mỹ Đình - 8 sân)</option>
                        <option value="Thanh Xuân">Quận Thanh Xuân (6 sân)</option>
                        <option value="Tây Hồ">Quận Tây Hồ (10 sân)</option>
                        <option value="Hà Đông">Quận Hà Đông (8 sân)</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">expand_more</span>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-3">
                    <button type="button" class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/25 transition transform active:scale-98 flex items-center justify-center gap-2">
                        <span>Tìm Kiếm Ngay</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick filter chips -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-6 text-xs font-semibold">
            <a href="#venue-list" class="px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-emerald-400">near_me</span>
                <span>Gần bạn (&lt; 2km)</span>
            </a>
            <a href="#venue-list" class="px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-blue-300">ac_unit</span>
                <span>Sân điều hòa mát mẻ</span>
            </a>
            <a href="#venue-list" class="px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-amber-300">directions_car</span>
                <span>Bãi đỗ ô tô miễn phí</span>
            </a>
            <span class="px-3.5 py-1.5 rounded-full bg-amber-400/20 border border-amber-300/30 text-amber-300 transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">percent</span>
                <span>Đang áp dụng Voucher -20%</span>
            </span>
        </div>

    </div>
</section>

<!-- Promotional Banner -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
    <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white rounded-2xl p-4 sm:p-5 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">bolt</span>
            </div>
            <div>
                <span class="font-bold text-sm sm:text-base block">FLASH SALE GIỜ VÀNG CHIỀU: GIẢM NGAY 20%</span>
                <p class="text-xs text-white/90">Áp dụng cho các khung giờ 14:00 - 17:00 tất cả các cụm sân hôm nay!</p>
            </div>
        </div>
        <a href="#venue-list" class="px-5 py-2.5 bg-white text-orange-600 font-bold text-xs sm:text-sm rounded-xl hover:bg-orange-50 shadow-md transition shrink-0">
            Xem Sân Đang Trống
        </a>
    </div>
</div>

<!-- Main Venue Explorer Grid -->
<section id="venue-list" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Hệ Thống Cơ Sở</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-headline">Cụm Sân Cầu Lông Nổi Bật</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Được trang bị sàn thảm Yonex BWF, hệ thống đèn chống lóa và máy lạnh toàn diện.</p>
        </div>
        <div class="hidden sm:block text-xs font-bold text-slate-400">
            Hiển thị 6 cơ sở tiêu chuẩn
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Venue Card 1 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden">
            <!-- Thumbnail with tags -->
            <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80" alt="GoSport Arena Cầu Giấy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Còn Sân Trống</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500 text-white shadow-md">
                        Đại Đấu Trường (12 sân)
                    </span>
                </div>

                <!-- Rating Badge -->
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                    <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                    <span>4.9</span>
                    <span class="text-slate-400 font-normal">(168)</span>
                </div>

                <!-- Distance / Area -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-1 truncate font-medium">
                        <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                        <span class="truncate">Quận Cầu Giấy, Hà Nội</span>
                    </div>
                    <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                        05:30 - 23:00
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                        GoSport Arena Cầu Giấy
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        Tổ hợp cụm sân cầu lông tiêu chuẩn quốc tế BWF với thảm Yonex Super Court 5mm, điều hòa trung tâm và phòng thay đồ cao cấp.
                    </p>

                    <!-- Amenities tags -->
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">sports_tennis</span>
                            <span>Thảm Yonex BWF</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">ac_unit</span>
                            <span>Điều hòa mát</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">directions_car</span>
                            <span>Bãi đỗ ô tô</span>
                        </span>
                    </div>
                </div>

                <!-- Pricing & CTA -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-blue-600 font-headline">140.000đ</span>
                            <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                        </div>
                    </div>

                    <button type="button" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5 cursor-pointer">
                        <span>Chọn Giờ Sân</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Venue Card 2 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden">
            <!-- Thumbnail with tags -->
            <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&auto=format&fit=crop&q=80" alt="GoSport Premium Mỹ Đình" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Còn Sân Trống</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-500 text-white shadow-md">
                        Tiêu Chuẩn (8 sân)
                    </span>
                </div>

                <!-- Rating Badge -->
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                    <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                    <span>4.8</span>
                    <span class="text-slate-400 font-normal">(124)</span>
                </div>

                <!-- Distance / Area -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-1 truncate font-medium">
                        <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                        <span class="truncate">Nam Từ Liêm, Hà Nội</span>
                    </div>
                    <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                        06:00 - 23:00
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                        GoSport Premium Mỹ Đình
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        Cụm sân hiện đại liền kề Sân vận động Mỹ Đình, trần cao 11m chống lóa chuyên dụng cho giải phong trào và bán chuyên.
                    </p>

                    <!-- Amenities tags -->
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">light_mode</span>
                            <span>Đèn Lumileds</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">lock</span>
                            <span>Locker Smart</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">shower</span>
                            <span>Tắm nóng lạnh</span>
                        </span>
                    </div>
                </div>

                <!-- Pricing & CTA -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-blue-600 font-headline">150.000đ</span>
                            <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                        </div>
                    </div>

                    <button type="button" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5 cursor-pointer">
                        <span>Chọn Giờ Sân</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Venue Card 3 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden">
            <!-- Thumbnail with tags -->
            <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img src="https://images.unsplash.com/photo-1511067007772-9da29974ce44?w=1200&auto=format&fit=crop&q=80" alt="GoSport Star Thanh Xuân" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Còn Sân Trống</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-500 text-white shadow-md">
                        Cơ Sở (6 sân)
                    </span>
                </div>

                <!-- Rating Badge -->
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                    <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                    <span>4.7</span>
                    <span class="text-slate-400 font-normal">(95)</span>
                </div>

                <!-- Distance / Area -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-1 truncate font-medium">
                        <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                        <span class="truncate">Thanh Xuân, Hà Nội</span>
                    </div>
                    <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                        06:00 - 22:30
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                        GoSport Star Thanh Xuân
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        Vị trí giao thông đắc địa tại trục Nguyễn Trãi - Khuất Duy Tiến, dịch vụ căng vợt điện tử lấy ngay và quầy giải khát chuyên nghiệp.
                    </p>

                    <!-- Amenities tags -->
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">handyman</span>
                            <span>Căng vợt Yonex</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">fastfood</span>
                            <span>Căn tin POS</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">two_wheeler</span>
                            <span>Bãi đỗ xe máy</span>
                        </span>
                    </div>
                </div>

                <!-- Pricing & CTA -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-blue-600 font-headline">130.000đ</span>
                            <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                        </div>
                    </div>

                    <button type="button" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5 cursor-pointer">
                        <span>Chọn Giờ Sân</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Venue Card 4 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden">
            <!-- Thumbnail with tags -->
            <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80" alt="GoSport Pro Tây Hồ" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Còn Sân Trống</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-500 text-white shadow-md">
                        View Hồ Tây (10 sân)
                    </span>
                </div>

                <!-- Rating Badge -->
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                    <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                    <span>4.9</span>
                    <span class="text-slate-400 font-normal">(142)</span>
                </div>

                <!-- Distance / Area -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-1 truncate font-medium">
                        <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                        <span class="truncate">Tây Hồ, Hà Nội</span>
                    </div>
                    <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                        05:30 - 23:00
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                        GoSport Pro Tây Hồ
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        Không gian thoáng đãng bên hồ Tây với thảm Gerflor chống trượt cao cấp, hệ thống camera AI tự động quay video highlight từng pha cầu.
                    </p>

                    <!-- Amenities tags -->
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">videocam</span>
                            <span>Camera AI Replay</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">layers</span>
                            <span>Thảm Gerflor</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">wifi</span>
                            <span>WiFi 500Mbps</span>
                        </span>
                    </div>
                </div>

                <!-- Pricing & CTA -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-blue-600 font-headline">160.000đ</span>
                            <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                        </div>
                    </div>

                    <button type="button" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5 cursor-pointer">
                        <span>Chọn Giờ Sân</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Venue Card 5 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden">
            <!-- Thumbnail with tags -->
            <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80" alt="GoSport Center Hà Đông" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Còn Sân Trống</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-500 text-white shadow-md">
                        Khán Đài (8 sân)
                    </span>
                </div>

                <!-- Rating Badge -->
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                    <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                    <span>4.8</span>
                    <span class="text-slate-400 font-normal">(88)</span>
                </div>

                <!-- Distance / Area -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-1 truncate font-medium">
                        <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                        <span class="truncate">Hà Đông, Hà Nội</span>
                    </div>
                    <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                        06:00 - 22:30
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                        GoSport Center Hà Đông
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        Đấu trường có khu vực khán đài cổ vũ quy mô lớn, phù hợp tổ chức giải đấu giao lưu doanh nghiệp và câu lạc bộ phong trào.
                    </p>

                    <!-- Amenities tags -->
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">stadium</span>
                            <span>Khán đài 200 chỗ</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">volume_up</span>
                            <span>Âm thanh giải đấu</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">groups</span>
                            <span>Phòng hội ý</span>
                        </span>
                    </div>
                </div>

                <!-- Pricing & CTA -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-blue-600 font-headline">120.000đ</span>
                            <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                        </div>
                    </div>

                    <button type="button" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5 cursor-pointer">
                        <span>Chọn Giờ Sân</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Venue Card 6 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden">
            <!-- Thumbnail with tags -->
            <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img src="https://images.unsplash.com/photo-1511067007772-9da29974ce44?w=800&auto=format&fit=crop&q=80" alt="GoSport Elite Long Biên" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Còn Sân Trống</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500 text-white shadow-md">
                        Đại Đấu Trường (14 sân)
                    </span>
                </div>

                <!-- Rating Badge -->
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-900 backdrop-blur-md flex items-center gap-1 shadow-md">
                    <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                    <span>4.9</span>
                    <span class="text-slate-400 font-normal">(210)</span>
                </div>

                <!-- Distance / Area -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-1 truncate font-medium">
                        <span class="material-symbols-outlined text-sm text-blue-400">location_on</span>
                        <span class="truncate">Long Biên, Hà Nội</span>
                    </div>
                    <span class="font-bold text-emerald-400 text-[11px] shrink-0 bg-slate-900/60 px-2 py-0.5 rounded-md">
                        05:00 - 23:30
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-headline font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">
                        GoSport Elite Long Biên
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        Cụm sân có quy mô lớn nhất chuỗi GoSport tại phía Đông Hà Nội với 14 sân đạt chuẩn BWF và trạm sạc xe điện thông minh.
                    </p>

                    <!-- Amenities tags -->
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">qr_code_scanner</span>
                            <span>Kiosk Barrier tự động</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">ev_station</span>
                            <span>Trạm sạc xe điện</span>
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs text-slate-500">local_parking</span>
                            <span>Bãi đỗ rộng lớn</span>
                        </span>
                    </div>
                </div>

                <!-- Pricing & CTA -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium block">Giá thuê chỉ từ</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-blue-600 font-headline">150.000đ</span>
                            <span class="text-[11px] text-slate-500 font-normal">/giờ</span>
                        </div>
                    </div>

                    <button type="button" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-98 flex items-center gap-1.5 cursor-pointer">
                        <span>Chọn Giờ Sân</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
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
            <button type="button" class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition transform active:scale-98 shrink-0 flex items-center gap-2 cursor-pointer">
                <span>Xem Sàn Ghép Kèo Ngay</span>
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </button>
        </div>
    </div>
</section>
@endsection
