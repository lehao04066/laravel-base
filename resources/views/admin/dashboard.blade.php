@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu & Chỉ Số Vận Hành - GoSport Admin')
@section('page_title', 'Báo Cáo Doanh Thu & Chỉ Số Vận Hành')

@section('content')
<div class="space-y-6">
    <!-- Top Action Ribbon -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700">Financial Intelligence</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Hệ thống vận hành trực tiếp
                </span>
            </div>
            <h1 class="font-headline font-bold text-2xl text-slate-900">Báo Cáo Doanh Thu &amp; Hiệu Suất Cụm Sân</h1>
            <p class="text-sm text-slate-500">Giám sát tổng doanh thu trực tuyến, bán lẻ POS quầy và tăng trưởng công suất khai thác sân.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-slate-100 p-1 rounded-xl flex items-center border border-slate-200">
                <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-blue-600 shadow-sm">Hôm nay</button>
                <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Tháng này</button>
                <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Quý IV</button>
            </div>
            <button onclick="window.print()" type="button" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 flex items-center gap-2 shadow-sm transition cursor-pointer">
                <span class="material-symbols-outlined text-base text-emerald-600">download</span>
                <span>Xuất Báo Cáo</span>
            </button>
            <button type="button" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-2 shadow-md shadow-blue-500/20 transition cursor-pointer">
                <span class="material-symbols-outlined text-base">tune</span>
                <span>Cấu Hình Giá Giờ Vàng</span>
            </button>
        </div>
    </div>

    <!-- 4 Core Executive Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tổng Doanh Thu Lũy Kế</span>
                    <h3 class="font-headline text-2xl font-extrabold text-slate-900 mt-1">248.500.000 <span class="text-sm text-blue-600 font-bold">đ</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Bao gồm cọc online + POS quầy</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">trending_up</span> +18.4%
                </span>
                <span class="text-slate-400">So với tháng trước</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Doanh Thu Đặt Sân Online</span>
                    <h3 class="font-headline text-2xl font-extrabold text-emerald-600 mt-1">163.100.000 <span class="text-sm font-bold">đ</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Qua cổng VNPay &amp; VietQR</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined">qr_code_2</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">check_circle</span> 100% tự động
                </span>
                <span class="text-slate-400">Tỷ lệ cọc chuẩn</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Doanh Thu Thu Ngân POS</span>
                    <h3 class="font-headline text-2xl font-extrabold text-amber-600 mt-1">85.400.000 <span class="text-sm font-bold">đ</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Nước uống, cầu, căng vợt</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined">point_of_sale</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-600 font-bold">Đã chốt sổ ca trực</span>
                <span class="text-amber-600 font-semibold">Khớp 100% két</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tổng Lượt Đặt &amp; Khai Thác</span>
                    <h3 class="font-headline text-2xl font-extrabold text-slate-900 mt-1">1.420 <span class="text-sm text-slate-400 font-normal">lượt / 26 sân</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Công suất khai thác trung bình 84%</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <span class="material-symbols-outlined">stadium</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-indigo-600 font-bold">Giờ vàng kín 98%</span>
                <span class="text-slate-400">17:00 - 21:00</span>
            </div>
        </div>
    </div>

    <!-- Charts & Facility Performance Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-headline font-bold text-lg text-slate-900">Biểu Đồ Doanh Thu Theo Giờ &amp; Khung Giờ Vàng</h2>
                    <p class="text-xs text-slate-500">Phân bổ doanh thu giữa khung giờ thường (Sáng/Chiều) và Giờ cao điểm (Tối)</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">Live Chart.js</span>
            </div>
            <div class="h-64 w-full relative">
                <canvas id="revenueTrendChart"></canvas>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-4 text-center">
                <div class="p-2 rounded-xl bg-slate-50">
                    <p class="text-[11px] font-bold text-slate-400 uppercase">Khung Sáng (06h-12h)</p>
                    <p class="font-headline font-bold text-sm text-slate-900 mt-0.5">32.400.000 đ</p>
                </div>
                <div class="p-2 rounded-xl bg-slate-50">
                    <p class="text-[11px] font-bold text-slate-400 uppercase">Khung Chiều (13h-17h)</p>
                    <p class="font-headline font-bold text-sm text-slate-900 mt-0.5">28.500.000 đ</p>
                </div>
                <div class="p-2 rounded-xl bg-blue-50/70 border border-blue-100">
                    <p class="text-[11px] font-bold text-blue-600 uppercase">Giờ Vàng Tối (17h-22h)</p>
                    <p class="font-headline font-bold text-sm text-blue-700 mt-0.5">85.600.000 đ</p>
                </div>
            </div>
        </div>

        <!-- Venue Contribution -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-headline font-bold text-lg text-slate-900">Tỷ Trọng Doanh Thu 3 Cụm Sân</h2>
                <span class="material-symbols-outlined text-slate-400">pie_chart</span>
            </div>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="venueContributionChart"></canvas>
            </div>
            <div class="space-y-2 mt-4 pt-4 border-t border-slate-100 text-xs">
                <div class="flex items-center justify-between py-1 border-b border-slate-50">
                    <span class="font-medium text-slate-700 truncate max-w-[150px]">GoSport Arena Cầu Giấy</span>
                    <span class="font-bold text-slate-900">12 Sân (45%)</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-50">
                    <span class="font-medium text-slate-700 truncate max-w-[150px]">GoSport Premium Mỹ Đình</span>
                    <span class="font-bold text-slate-900">8 Sân (30%)</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0">
                    <span class="font-medium text-slate-700 truncate max-w-[150px]">GoSport Star Thanh Xuân</span>
                    <span class="font-bold text-slate-900">6 Sân (25%)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Venue Breakdown Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-headline font-bold text-lg text-slate-900">Chi Tiết Hiệu Suất Theo Cơ Sở</h2>
                <p class="text-xs text-slate-500">Thống kê công suất hoạt động, số giờ đặt và tỷ lệ check-in thành công</p>
            </div>
            <span class="px-3 py-1.5 rounded-xl bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm text-blue-600">visibility</span>
                <span>Toàn Chuỗi 4 Cơ Sở</span>
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold text-slate-400 uppercase border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Cơ sở vận hành</th>
                        <th class="py-3.5 px-6">Địa chỉ</th>
                        <th class="py-3.5 px-6 text-center">Quy mô</th>
                        <th class="py-3.5 px-6 text-center">Giờ mở cửa</th>
                        <th class="py-3.5 px-6 text-right">Giá bình quân/h</th>
                        <th class="py-3.5 px-6 text-center">Trạng thái IoT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-6 font-bold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                GoSport Arena Cầu Giấy
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-500 max-w-xs truncate">26 Phạm Văn Đồng, Cầu Giấy, Hà Nội</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold bg-blue-50 text-blue-700">12 Sân</span>
                        </td>
                        <td class="py-3.5 px-6 text-center font-mono">05:30 - 23:00</td>
                        <td class="py-3.5 px-6 text-right font-bold text-slate-900 font-mono">140.000 - 220.000 đ</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Online
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-6 font-bold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                GoSport Premium Mỹ Đình
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-500 max-w-xs truncate">Lê Đức Thọ, Nam Từ Liêm, Hà Nội</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold bg-blue-50 text-blue-700">8 Sân</span>
                        </td>
                        <td class="py-3.5 px-6 text-center font-mono">06:00 - 23:00</td>
                        <td class="py-3.5 px-6 text-right font-bold text-slate-900 font-mono">150.000 - 240.000 đ</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Online
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-6 font-bold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                GoSport Star Thanh Xuân
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-500 max-w-xs truncate">18 Nguyễn Trãi, Thanh Xuân, Hà Nội</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold bg-blue-50 text-blue-700">6 Sân</span>
                        </td>
                        <td class="py-3.5 px-6 text-center font-mono">06:00 - 22:30</td>
                        <td class="py-3.5 px-6 text-right font-bold text-slate-900 font-mono">130.000 - 200.000 đ</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Online
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-6 font-bold text-slate-900">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                GoSport Pro Tây Hồ
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-500 max-w-xs truncate">88 Lạc Long Quân, Tây Hồ, Hà Nội</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold bg-blue-50 text-blue-700">10 Sân</span>
                        </td>
                        <td class="py-3.5 px-6 text-center font-mono">05:30 - 23:00</td>
                        <td class="py-3.5 px-6 text-right font-bold text-slate-900 font-mono">160.000 - 250.000 đ</td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Online
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart !== 'undefined') {
            // Revenue Trend Chart
            const ctxTrend = document.getElementById('revenueTrendChart')?.getContext('2d');
            if (ctxTrend) {
                new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'],
                        datasets: [
                            {
                                label: 'Doanh thu giờ vàng (triệu đ)',
                                data: [4.2, 8.5, 9.1, 7.0, 8.8, 14.5, 24.8, 28.2, 12.1],
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                tension: 0.35,
                                fill: true,
                                pointBackgroundColor: '#2563eb'
                            },
                            {
                                label: 'Doanh thu POS bán lẻ (triệu đ)',
                                data: [1.1, 2.3, 3.0, 2.5, 2.8, 4.2, 8.0, 9.5, 4.0],
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.05)',
                                tension: 0.35,
                                fill: true,
                                pointBackgroundColor: '#10b981'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(val) { return val + 'M'; }
                                }
                            }
                        }
                    }
                });
            }

            // Venue Contribution Pie
            const ctxDonut = document.getElementById('venueContributionChart')?.getContext('2d');
            if (ctxDonut) {
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: ['GoSport Cầu Giấy', 'GoSport Mỹ Đình', 'GoSport Thanh Xuân'],
                        datasets: [{
                            data: [45, 30, 25],
                            backgroundColor: ['#2563eb', '#10b981', '#f59e0b']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection
