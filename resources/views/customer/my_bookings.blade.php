@extends('layouts.main')

@section('title', 'Đơn Đặt Sân Của Tôi - GoSport')

@section('content')
<div x-data="myBookingsApp()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Lịch sử đặt sân</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-headline mt-1">Đơn Đặt Sân Của Tôi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Theo dõi lịch đặt sân, vé điện tử và mã QR check-in.</p>
        </div>

        <a href="{{ route('home') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
            <span class="material-symbols-outlined text-sm">add_circle</span>
            <span>Đặt Thêm Sân Mới</span>
        </a>

        <a href="{{ route('my-bookings', ['tab' => 'completed']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 shrink-0 {{ $tab === 'completed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            <span>Lịch Sử Đã Chơi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $completedCount }}</span>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-4 mb-8 overflow-x-auto">
        <a href="{{ route('my-bookings', ['tab' => 'upcoming']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 shrink-0 {{ $tab === 'upcoming' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            <span>Sắp Diễn Ra (Đã Xác Nhận)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'upcoming' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $upcomingCount }}</span>
        </a>

        <a href="{{ route('my-bookings', ['tab' => 'cancelled']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 shrink-0 {{ $tab === 'cancelled' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            <span>Đã Hủy</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'cancelled' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $cancelledCount }}</span>
        </a>

        <a href="{{ route('my-bookings', ['tab' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 shrink-0 {{ $tab === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            <span>Tất Cả</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $allCount }}</span>
        </a>
    </div>

    <!-- Bookings List -->
    @if($bookings->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">confirmation_number</span>
            <h3 class="text-base font-bold text-slate-800">Không có đơn đặt sân nào trong mục này</h3>
            <p class="text-xs text-slate-500 mt-1">Bạn chưa có đơn đặt sân nào tương ứng.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 px-5 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl">Khám phá và đặt sân ngay</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-2xl">sports_tennis</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-headline font-bold text-lg text-slate-900">{{ $booking->venue->name ?? 'Cụm sân' }}</span>
                                    @if($booking->status === 'confirmed')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">Đã Xác Nhận</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">Đã Hủy</span>
                                    @elseif($booking->status === 'checked_in')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-violet-100 text-violet-800">Đã Check-in</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">Đã Hoàn Thành</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">{{ $booking->status }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Mã đơn: <strong class="text-slate-800 font-mono">{{ $booking->booking_code }}</strong> • Ngày đặt: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Date & Pricing -->
                        <div class="flex items-center gap-6">
                            <div class="text-left lg:text-right">
                                <span class="text-[11px] text-slate-400 block">Ngày thi đấu</span>
                                <span class="text-base font-bold text-blue-600 font-headline">{{ $booking->booking_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-left lg:text-right">
                                <span class="text-[11px] text-slate-400 block">Thanh toán tại sân</span>
                                <span class="text-xl font-black text-slate-900 font-headline">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Booked Slots -->
                    <div class="py-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        @foreach($booking->items as $item)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-blue-600">check_circle</span>
                                    <div>
                                        <strong class="text-slate-800">{{ $item->court->name ?? 'Sân con' }}</strong>
                                        <span class="text-slate-500 block">{{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }} ({{ $item->duration_minutes }} phút)</span>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-800">{{ number_format($item->amount, 0, ',', '.') }} đ</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <div class="text-xs text-slate-500">
                            Hình thức: <span class="font-semibold text-slate-700">Thanh toán tại sân khi nhận sân</span>
                        </div>

                        <div class="flex items-center gap-4 text-xs">
                            @if($booking->check_in_token && $booking->status !== 'cancelled')
                                <button type="button" @click="openQrModal(@js(route('my-bookings.qr', $booking->booking_code)), @js($booking->booking_code), @js($booking->venue->name ?? 'GoSport'))" class="text-violet-600 hover:text-violet-700 hover:underline font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">qr_code_2</span>
                                    <span>Xem mã QR check-in</span>
                                </button>
                            @endif
                            @if($booking->status === 'confirmed')
                                <form method="POST" action="{{ route('my-bookings.cancel', $booking->booking_code) }}" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt sân này? Khung giờ sẽ được giải phóng.')">
                                    @csrf
                                    <input type="hidden" name="reason" value="Khách hàng chủ động yêu cầu hủy trên website">
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 hover:underline font-semibold flex items-center gap-1 cursor-pointer">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        <span>Hủy đặt sân</span>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('my-bookings.show', $booking->booking_code) }}" class="text-blue-600 hover:text-blue-700 hover:underline font-bold flex items-center gap-1">
                                <span>Xem chi tiết vé</span>
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<div x-cloak x-show="showQr" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60" @click.self="closeQrModal()">
    <div class="w-full max-w-sm rounded-3xl bg-white p-7 text-center shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-extrabold text-slate-900">Vé điện tử QR</h2>
            <button type="button" @click="closeQrModal()" class="text-slate-400 hover:text-slate-700"><span class="material-symbols-outlined">close</span></button>
        </div>
        <p class="text-xs text-slate-500 mb-4" x-text="venue"></p>
        <div class="mx-auto w-fit rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
            <img :src="qrUrl" alt="Mã QR check-in" class="w-52 h-52">
        </div>
        <p class="mt-4 text-xs text-slate-500">Mã đặt sân</p>
        <p class="font-mono font-bold text-slate-900" x-text="bookingCode"></p>
        <button type="button" @click="closeQrModal()" class="mt-5 w-full rounded-xl bg-blue-600 px-4 py-3 text-xs font-bold text-white hover:bg-blue-700">Đóng</button>
    </div>
</div>

<script>
    function myBookingsApp() {
        return {
            showQr: false, bookingCode: '', venue: '', qrUrl: '',
            openQrModal(url, code, venue) { this.qrUrl = url; this.bookingCode = code; this.venue = venue; this.showQr = true; },
            closeQrModal() { this.showQr = false; }
        };
    }
</script>
@endsection
