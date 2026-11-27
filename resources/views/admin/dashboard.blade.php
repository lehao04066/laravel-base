@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu & Chỉ Số Vận Hành - GoSport Admin')
@section('page_title', 'Báo Cáo Doanh Thu & Chỉ Số Vận Hành')

@section('content')
<div class="space-y-6">
    <!-- Top Action Ribbon -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700">GoSport Admin</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Hệ thống vận hành trực tiếp
                </span>
            </div>
            <h1 class="font-headline font-bold text-2xl text-slate-900">Báo Cáo Doanh Thu &amp; Hiệu Suất Cụm Sân</h1>
            <p class="text-sm text-slate-500">Giám sát tổng doanh thu đặt sân, tình trạng hoạt động và công suất khai thác sân con.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 flex items-center gap-2 shadow-sm transition">
                <span class="material-symbols-outlined text-base text-blue-600">receipt_long</span>
                <span>Xem Đơn Đặt Sân</span>
            </a>
            <a href="{{ route('admin.pricing.index') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-2 shadow-md shadow-blue-500/20 transition">
                <span class="material-symbols-outlined text-base">tune</span>
                <span>Cấu Hình Bảng Giá</span>
            </a>
        </div>
    </div>

    <!-- 4 Core Executive Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tổng Doanh Thu Đặt Sân</span>
                    <h3 class="font-headline text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalRevenue, 0, ',', '.') }} <span class="text-sm text-blue-600 font-bold">đ</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Từ các đơn đặt đã xác nhận</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">trending_up</span> Thanh toán tại sân
                </span>
                <span class="text-slate-400">Đơn xác nhận ngay</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Đơn Đặt Đã Xác Nhận</span>
                    <h3 class="font-headline text-2xl font-extrabold text-emerald-600 mt-1">{{ $confirmedBookingsCount }} <span class="text-sm font-bold">đơn</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tổng số lượt: {{ $totalBookingsCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">event_available</span> Lịch sân tự động
                </span>
                <span class="text-slate-400">Không trùng slot</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Số Cụm Sân Quản Lý</span>
                    <h3 class="font-headline text-2xl font-extrabold text-amber-600 mt-1">{{ $venuesCount }} <span class="text-sm font-bold">cụm</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Hà Nội &amp; các chi nhánh</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined">stadium</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('admin.venues.index') }}" class="text-amber-600 font-bold hover:underline flex items-center gap-1">
                    <span>Xem danh sách cụm sân</span> &rarr;
                </a>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sân Con Đang Hoạt Động</span>
                    <h3 class="font-headline text-2xl font-extrabold text-indigo-600 mt-1">{{ $activeCourtsCount }} <span class="text-sm text-slate-400 font-normal">sân</span></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Sẵn sàng phục vụ người chơi</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <span class="material-symbols-outlined">sports_tennis</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-indigo-600 font-bold">Tiêu chuẩn BWF</span>
                <span class="text-slate-400">Thảm thi đấu</span>
            </div>
        </div>
    </div>

    <!-- Facility Matrix -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline font-bold text-lg text-slate-900">Tình Trạng Vận Hành Các Cụm Sân</h2>
                <p class="text-xs text-slate-500">Giám sát tổng số sân con, giờ mở cửa và trạng thái sẵn sàng đón khách.</p>
            </div>
            <a href="{{ route('admin.venues.create') }}" class="px-3.5 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs rounded-xl transition inline-flex items-center gap-1.5 self-start sm:self-auto">
                <span class="material-symbols-outlined text-sm">add</span>
                <span>Thêm Cụm Sân Mới</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3 px-6">Tên Cụm Sân</th>
                        <th class="py-3 px-6">Địa Chỉ</th>
                        <th class="py-3 px-6 text-center">Quy Mô Sân Con</th>
                        <th class="py-3 px-6 text-center">Giờ Mở Cửa</th>
                        <th class="py-3 px-6 text-center">Trạng Thái</th>
                        <th class="py-3 px-6 text-right">Quản Lý</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($venues as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $v->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $v->name }}
                                </div>
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 max-w-xs truncate">{{ $v->address }}, {{ $v->district }}</td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full font-bold bg-blue-50 text-blue-700">{{ $v->courts_count }} Sân</span>
                            </td>
                            <td class="py-3.5 px-6 text-center font-mono">{{ substr($v->opening_time, 0, 5) }} - {{ substr($v->closing_time, 0, 5) }}</td>
                            <td class="py-3.5 px-6 text-center">
                                @if($v->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đang hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Ngừng hoạt động
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.venues.edit', $v->id) }}" class="text-blue-600 font-bold hover:underline">Chỉnh sửa</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Chưa có cụm sân nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="font-headline font-bold text-lg text-slate-900">Các Lượt Đặt Sân Gần Đây</h2>
                <p class="text-xs text-slate-500">Danh sách các đơn đặt mới nhất trên toàn hệ thống.</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                Xem toàn bộ đơn &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3 px-6">Mã Đơn</th>
                        <th class="py-3 px-6">Khách Hàng</th>
                        <th class="py-3 px-6">Cụm Sân</th>
                        <th class="py-3 px-6">Ngày Đặt</th>
                        <th class="py-3 px-6">Tổng Tiền</th>
                        <th class="py-3 px-6">Trạng Thái</th>
                        <th class="py-3 px-6 text-right">Chi Tiết</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentBookings as $b)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-slate-900">{{ $b->booking_code }}</td>
                            <td class="py-3.5 px-6">
                                <span class="font-semibold text-slate-800">{{ $b->user->name ?? 'N/A' }}</span>
                                <span class="text-slate-400 block text-[11px]">{{ $b->user->phone ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-6 text-slate-600 font-medium">{{ $b->venue->name ?? 'N/A' }}</td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">{{ $b->booking_date->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-900">{{ number_format($b->final_amount, 0, ',', '.') }} đ</td>
                            <td class="py-3.5 px-6">
                                @if($b->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đã xác nhận
                                    </span>
                                @elseif($b->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Đã hủy
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $b->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="text-blue-600 font-bold hover:underline">Xem đơn</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Chưa có đơn đặt nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
