@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Đặt Sân: ' . $booking->booking_code . ' - GoSport Admin')
@section('page_title', 'Chi Tiết Đơn: ' . $booking->booking_code)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Top Action Ribbon -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            <span>Quay lại danh sách đơn đặt</span>
        </a>

        @if($booking->status === 'confirmed')
            <button type="button" onclick="document.getElementById('cancel-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-bold transition">
                <span class="material-symbols-outlined text-base">cancel</span>
                <span>Hủy Đơn Đặt Này</span>
            </button>
        @endif
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mã Đơn Đặt Sân</span>
                <h1 class="text-2xl font-black text-slate-900 font-mono mt-1">{{ $booking->booking_code }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">Tạo lúc: {{ $booking->created_at->format('H:i, d/m/Y') }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($booking->status === 'confirmed')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Đã xác nhận
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Đã hủy
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                        {{ $booking->status }}
                    </span>
                @endif

                <span class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                    {{ $booking->payment_method === 'pay_at_venue' ? 'Thanh toán tại sân' : $booking->payment_method }}
                </span>
                <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                    {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
            </div>
        </div>

        <!-- 2 Column Client & Venue Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-blue-600">person</span>
                    <span>Thông Tin Khách Hàng</span>
                </h3>
                <div class="text-sm font-bold text-slate-900">{{ $booking->user->name ?? 'N/A' }}</div>
                <div class="text-xs text-slate-600">Số điện thoại: <span class="font-semibold">{{ $booking->user->phone ?? 'N/A' }}</span></div>
                <div class="text-xs text-slate-600">Email: <span class="font-semibold">{{ $booking->user->email ?? 'N/A' }}</span></div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-blue-600">stadium</span>
                    <span>Thông Tin Cụm Sân</span>
                </h3>
                <div class="text-sm font-bold text-slate-900">{{ $booking->venue->name ?? 'N/A' }}</div>
                <div class="text-xs text-slate-600">Địa chỉ: <span class="font-semibold">{{ $booking->venue->address ?? 'N/A' }}, {{ $booking->venue->district ?? '' }}</span></div>
                <div class="text-xs text-slate-600">Hotline: <span class="font-semibold">{{ $booking->venue->phone ?? 'N/A' }}</span></div>
            </div>
        </div>

        <!-- Court Items Table -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg text-blue-600">calendar_month</span>
                <span>Chi Tiết Khung Giờ Sân Đặt (Ngày: {{ $booking->booking_date->format('d/m/Y') }})</span>
            </h3>

            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Sân Con</th>
                            <th class="px-4 py-3">Giờ Bắt Đầu</th>
                            <th class="px-4 py-3">Giờ Kết Thúc</th>
                            <th class="px-4 py-3">Thời Lượng</th>
                            <th class="px-4 py-3">Đơn Giá / Giờ</th>
                            <th class="px-4 py-3 text-right">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($booking->items as $item)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $item->court->name ?? 'Sân' }}
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-slate-800">
                                    {{ substr($item->start_time, 0, 5) }}
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-slate-800">
                                    {{ substr($item->end_time, 0, 5) }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ $item->duration_minutes }} phút
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ number_format($item->hourly_rate, 0, ',', '.') }} đ
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-slate-900 text-right">
                                    {{ number_format($item->amount, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200 font-bold text-slate-900">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right text-xs uppercase tracking-wider">Tổng Cộng Cần Thanh Toán (Tại Sân):</td>
                            <td class="px-4 py-3 text-right text-base text-blue-600">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($booking->notes)
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                <span class="font-bold">Ghi chú của khách:</span> {{ $booking->notes }}
            </div>
        @endif

        <!-- Status History Timeline -->
        <div class="pt-4 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg text-slate-500">history</span>
                <span>Lịch Sử Thay Đổi Trạng Thái Đơn</span>
            </h3>

            <div class="space-y-3">
                @forelse($booking->statusHistories->sortByDesc('created_at') as $h)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 text-xs flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800">
                                {{ $h->new_status === 'confirmed' ? 'Xác nhận đơn' : ($h->new_status === 'cancelled' ? 'Hủy đơn' : $h->new_status) }}
                            </span>
                            <span class="text-slate-400 mx-1">—</span>
                            <span class="text-slate-600">{{ $h->reason ?? 'Không có lý do' }}</span>
                            @if($h->user)
                                <span class="text-slate-400"> (bởi {{ $h->user->name }})</span>
                            @endif
                        </div>
                        <div class="text-[11px] text-slate-400">
                            {{ $h->created_at->format('H:i d/m/Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Chưa có lịch sử trạng thái.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancel-modal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100">
        <h3 class="text-lg font-bold text-slate-900 mb-2">Xác Nhận Hủy Đơn Đặt Sân</h3>
        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
            Khung giờ của đơn này sẽ ngay lập tức được giải phóng để khách hàng khác có thể đặt. Lịch sử hủy sẽ được lưu trữ đầy đủ.
        </p>

        <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lý do hủy đơn *</label>
                <textarea name="reason" rows="3" required placeholder="Ví dụ: Khách gọi điện báo bận đột xuất..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Đóng
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20">
                    Xác Nhận Hủy Đơn
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
