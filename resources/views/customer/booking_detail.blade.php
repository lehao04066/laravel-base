@extends('layouts.main')

@section('title', 'Chi Tiết Đơn Đặt Sân - ' . $booking->booking_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl space-y-6">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <a href="{{ route('my-bookings') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline mb-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    <span>Quay lại danh sách đơn</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-headline">Chi Tiết Đơn Đặt Sân</h1>
                <p class="text-xs text-slate-500 mt-1">Mã đơn đặt sân: <strong class="text-slate-900 font-mono text-sm">{{ $booking->booking_code }}</strong></p>
            </div>

            <div class="flex items-center gap-2">
                @if($booking->status === 'confirmed')
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        ĐÃ XÁC NHẬN
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                        ĐÃ HỦY
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                        {{ strtoupper($booking->status) }}
                    </span>
                @endif
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                    THANH TOÁN TẠI SÂN
                </span>
            </div>
        </div>

        <!-- Venue & Time Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                <h3 class="font-bold text-slate-900 text-sm font-headline flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-lg">stadium</span>
                    <span>Thông Tin Cụm Sân</span>
                </h3>
                <p class="text-base font-bold text-slate-800">{{ $booking->venue->name }}</p>
                <p class="text-xs text-slate-500 leading-relaxed">{{ $booking->venue->address }}, {{ $booking->venue->district }}, {{ $booking->venue->city }}</p>
                <p class="text-xs text-slate-500">Hotline: <strong class="text-slate-800">{{ $booking->venue->phone }}</strong></p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                <h3 class="font-bold text-slate-900 text-sm font-headline flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-lg">calendar_month</span>
                    <span>Lịch Thi Đấu</span>
                </h3>
                <p class="text-base font-bold text-blue-600 font-headline">Ngày {{ $booking->booking_date->format('d/m/Y') }}</p>
                <div class="space-y-1">
                    @foreach($booking->items as $item)
                        <div class="text-xs text-slate-700 flex justify-between">
                            <span>{{ $item->court->name ?? 'Sân' }}:</span>
                            <strong>{{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Booking Code Presentation Banner -->
        <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-900 via-indigo-950 to-slate-900 text-white text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-300">Phiếu Nhận Sân GoSport</span>
            <div class="my-4">
                <div class="text-3xl sm:text-4xl font-black font-mono tracking-wider text-emerald-400">
                    {{ $booking->booking_code }}
                </div>
                <p class="text-xs text-slate-300 mt-2">
                    Vui lòng xuất trình mã đơn đặt sân này tại quầy lễ tân để thanh toán và nhận sân.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-xs font-medium text-slate-200">
                <span class="material-symbols-outlined text-sm text-emerald-400">check_circle</span>
                <span>Trạng thái: {{ $booking->status === 'confirmed' ? 'Đã xác nhận giữ chỗ' : ($booking->status === 'cancelled' ? 'Đơn đã hủy' : $booking->status) }}</span>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-500">Hình thức thanh toán:</span>
                <span class="font-bold text-slate-800">Thanh toán tại sân khi nhận sân</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Trạng thái thanh toán:</span>
                <span class="font-bold {{ $booking->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán (Thanh toán tại sân)' }}
                </span>
            </div>
            <div class="pt-2 border-t border-slate-200 flex justify-between text-sm">
                <strong class="text-slate-900">Tổng thanh toán:</strong>
                <strong class="text-blue-600 font-headline text-lg">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</strong>
            </div>
        </div>

        @if($booking->notes)
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700">
                <span class="font-bold">Ghi chú của bạn:</span> {{ $booking->notes }}
            </div>
        @endif

        <!-- Status History -->
        @if($booking->statusHistories->isNotEmpty())
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Lịch sử trạng thái đơn:</h4>
                <div class="space-y-1.5">
                    @foreach($booking->statusHistories->sortByDesc('created_at') as $h)
                        <div class="text-xs text-slate-600 flex items-center justify-between p-2 rounded-lg bg-slate-50">
                            <span>
                                <strong>{{ $h->new_status === 'confirmed' ? 'Xác nhận đơn' : ($h->new_status === 'cancelled' ? 'Hủy đơn' : $h->new_status) }}</strong>
                                @if($h->reason) — {{ $h->reason }} @endif
                            </span>
                            <span class="text-[11px] text-slate-400">{{ $h->created_at->format('H:i d/m/Y') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-wrap justify-between items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('my-bookings') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                &larr; Về danh sách đơn
            </a>

            <div class="flex items-center gap-2">
                @if($booking->status === 'confirmed')
                    <form method="POST" action="{{ route('my-bookings.cancel', $booking->booking_code) }}" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt sân này? Khung giờ sẽ được giải phóng.')">
                        @csrf
                        <input type="hidden" name="reason" value="Khách hàng chủ động yêu cầu hủy trên website">
                        <button type="submit" class="px-5 py-2.5 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold rounded-xl border border-rose-200 transition">
                            Hủy Đơn Đặt Sân
                        </button>
                    </form>
                @endif

                <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">print</span>
                    <span>In Phiếu Đặt Sân</span>
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
