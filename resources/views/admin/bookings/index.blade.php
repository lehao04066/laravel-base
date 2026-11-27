@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Đặt Sân - GoSport Admin')
@section('page_title', 'Quản Lý Đơn Đặt Sân')

@section('content')
<div class="space-y-6">
    <!-- Top Action Ribbon -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="font-headline font-bold text-2xl text-slate-900">Quản Lý Đơn Đặt Sân</h1>
            <p class="text-sm text-slate-500">Xem, tra cứu, lọc đơn theo ngày hoặc trạng thái, xem chi tiết và hủy đơn hợp lệ.</p>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Tổng Lượt Đặt</span>
                <p class="text-2xl font-extrabold text-slate-900 font-headline mt-1">{{ $totalCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Đã Xác Nhận</span>
                <p class="text-2xl font-extrabold text-emerald-600 font-headline mt-1">{{ $confirmedCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Đã Hủy Đơn</span>
                <p class="text-2xl font-extrabold text-rose-600 font-headline mt-1">{{ $cancelledCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined">cancel</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Đơn Đặt Hôm Nay</span>
                <p class="text-2xl font-extrabold text-indigo-600 font-headline mt-1">{{ $todayBookingsCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined">today</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tìm kiếm</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Mã đơn, tên khách, số điện thoại..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Ngày đặt sân</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                    <option value="all">Tất cả</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Cụm sân</label>
                <select name="venue_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                    <option value="all">Tất cả cụm sân</option>
                    @foreach($venues as $v)
                        <option value="{{ $v->id }}" {{ request('venue_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Lọc Đơn
                </button>
                @if(request()->hasAny(['q', 'date', 'status', 'venue_id']))
                    <a href="{{ route('admin.bookings.index') }}" class="p-2.5 text-slate-500 hover:text-slate-800 border border-slate-200 rounded-xl flex items-center justify-center transition" title="Xóa bộ lọc">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4">Mã Đơn / Khách Hàng</th>
                        <th class="px-5 py-4">Cụm Sân</th>
                        <th class="px-5 py-4">Sân Con &amp; Khung Giờ</th>
                        <th class="px-5 py-4">Ngày Đặt</th>
                        <th class="px-5 py-4">Tổng Tiền</th>
                        <th class="px-5 py-4">Trạng Thái</th>
                        <th class="px-5 py-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 font-mono text-xs">{{ $b->booking_code }}</div>
                                <div class="text-xs text-slate-600 font-semibold mt-0.5">{{ $b->user->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $b->user->phone ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold text-slate-800">
                                {{ $b->venue->name ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4 text-xs">
                                @foreach($b->items as $item)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 font-medium text-slate-800 mb-1 mr-1">
                                        <span class="font-bold text-blue-600">{{ $item->court->name ?? 'Sân' }}</span>
                                        <span class="text-slate-400">|</span>
                                        <span>{{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold text-slate-800">
                                {{ $b->booking_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 text-xs font-extrabold text-slate-900">
                                {{ number_format($b->final_amount, 0, ',', '.') }} đ
                                <div class="text-[10px] font-normal text-slate-400">Thanh toán tại sân</div>
                            </td>
                            <td class="px-5 py-4 text-xs">
                                @if($b->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đã xác nhận
                                    </span>
                                @elseif($b->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Đã hủy
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px]">
                                        {{ $b->status }}
                                    </span>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $b->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="p-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition" title="Xem chi tiết đơn">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>

                                    @if($b->status === 'confirmed')
                                        <button type="button" onclick="cancelBookingPrompt({{ $b->id }}, '{{ $b->booking_code }}')" class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 transition" title="Hủy đơn đặt sân">
                                            <span class="material-symbols-outlined text-lg">cancel</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Không tìm thấy đơn đặt sân nào theo bộ lọc hiện tại.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Hidden Cancellation Form -->
<form id="cancel-form" method="POST" action="" class="hidden">
    @csrf
    <input type="hidden" name="reason" id="cancel-reason">
</form>

<script>
    function cancelBookingPrompt(bookingId, bookingCode) {
        const reason = prompt(`Nhập lý do hủy đơn ${bookingCode} (khung giờ sẽ được giải phóng ngay lập tức):`, 'Khách yêu cầu hủy qua điện thoại');
        if (reason && reason.trim()) {
            const form = document.getElementById('cancel-form');
            form.action = `/admin/bookings/${bookingId}/cancel`;
            document.getElementById('cancel-reason').value = reason.trim();
            form.submit();
        }
    }
</script>
@endsection
