@extends('layouts.main')

@section('title', $venue->name . ' - Chi Tiết Sân & Chọn Giờ Đặt Sân')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="bookingApp()">
    
    <!-- Venue Header & Info Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md mb-8">
        <div class="flex flex-col lg:flex-row items-start justify-between gap-6">
            <!-- Left Info (Takes all available width) -->
            <div class="flex-1 min-w-0 pr-0 lg:pr-6">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Đang Mở Cửa ({{ substr($venue->opening_time, 0, 5) }} - {{ substr($venue->closing_time, 0, 5) }})</span>
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">sports_tennis</span>
                        <span>{{ $courts->count() }} Sân Tiêu Chuẩn BWF</span>
                    </span>
                </div>

                <h1 class="font-headline font-extrabold text-2xl sm:text-3xl lg:text-4xl text-slate-900 tracking-tight leading-snug">
                    {{ $venue->name }}
                </h1>
                
                <p class="text-xs sm:text-sm text-slate-600 mt-2.5 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-lg shrink-0">location_on</span>
                    <span>{{ $venue->address }}, {{ $venue->district }}, {{ $venue->city }}</span>
                </p>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-emerald-600 text-base shrink-0">call</span>
                    <span>Hotline đặt sân: <strong class="text-slate-900 font-bold">{{ $venue->phone }}</strong></span>
                </p>
                <p class="text-xs sm:text-sm text-slate-600 mt-3 max-w-3xl leading-relaxed">
                    {{ $venue->description ?? 'Tổ hợp sân cầu lông cao cấp với thảm tiêu chuẩn BWF chuyên nghiệp, hệ thống đèn chống lóa Lumileds và máy lạnh trung tâm.' }}
                </p>
            </div>

            <!-- Right Price Box (Clean fixed width on desktop) -->
            <div class="w-full lg:w-72 shrink-0 p-5 bg-gradient-to-br from-blue-50/90 via-indigo-50/40 to-slate-50 rounded-2xl border border-blue-100/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between lg:justify-end gap-2 text-amber-500 mb-2">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">star</span>
                        <span class="font-bold text-slate-900 text-base">{{ number_format($venue->rating_avg ?? 5.0, 1) }}</span>
                        <span class="text-xs text-slate-500">({{ $venue->reviews_count ?? 120 }} đánh giá)</span>
                    </div>
                </div>
                <div class="text-left lg:text-right">
                    <span class="text-xs text-slate-500 font-medium block">Giá thuê cơ bản từ</span>
                    <div class="flex items-baseline justify-start lg:justify-end gap-1 mt-0.5">
                        <span class="text-2xl sm:text-3xl font-extrabold text-blue-600 font-headline tracking-tight">
                            {{ number_format($venue->pricingRules->min('price_per_hour') ?? 120000, 0, ',', '.') }}đ
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">/giờ</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-blue-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Thanh toán:</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                        <span class="material-symbols-outlined text-xs">payments</span> Thanh toán tại sân
                    </span>
                </div>
            </div>
        </div>

        <!-- Venue Gallery Strip -->
        <div class="mt-6 pt-6 border-t border-slate-100">
            @php
                $gallery = is_array($venue->gallery) ? $venue->gallery : (is_string($venue->gallery) ? json_decode($venue->gallery, true) : []);
                if (empty($gallery) && !empty($venue->featured_image)) {
                    $gallery = [$venue->featured_image];
                }
            @endphp

            @if(!empty($gallery) && count($gallery) >= 3)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach(array_slice($gallery, 0, 3) as $img)
                        <div class="h-48 sm:h-56 rounded-2xl overflow-hidden bg-slate-100 shadow-sm">
                            <img src="{{ $img }}" alt="{{ $venue->name }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80';" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </div>
                    @endforeach
                </div>
            @elseif(!empty($gallery) && count($gallery) > 0)
                <div class="h-64 sm:h-80 rounded-2xl overflow-hidden bg-slate-100 shadow-sm">
                    <img src="{{ $gallery[0] }}" alt="{{ $venue->name }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80';" class="w-full h-full object-cover">
                </div>
            @else
                <div class="h-64 sm:h-80 rounded-2xl overflow-hidden bg-slate-100 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80" alt="{{ $venue->name }}" class="w-full h-full object-cover">
                </div>
            @endif
        </div>
    </div>

    <!-- Booking Form & Visual Slot Selection -->
    <form method="POST" action="{{ route('booking.submit') }}" id="booking-form">
        @csrf
        <input type="hidden" name="venue_id" value="{{ $venue->id }}">
        <input type="hidden" name="booking_date" :value="selectedDate">

        <!-- Hidden input fields for selected slots -->
        <template x-for="(slot, index) in selectedSlots" :key="slot.court_id + '_' + slot.start_time">
            <div>
                <input type="hidden" :name="'slots[' + index + '][court_id]'" :value="slot.court_id">
                <input type="hidden" :name="'slots[' + index + '][start_time]'" :value="slot.start_time">
                <input type="hidden" :name="'slots[' + index + '][end_time]'" :value="slot.end_time">
            </div>
        </template>

        <!-- Step 1: Select Date -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Bước 1</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-headline mt-0.5">Chọn Ngày Thi Đấu</h2>
                    <p class="text-xs text-slate-500 mt-1">Chọn ngày bạn muốn thi đấu để xem biểu giá và các khung giờ còn trống.</p>
                </div>

                <!-- Custom Date Picker Input -->
                <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200">
                    <span class="material-symbols-outlined text-slate-500 text-lg">calendar_today</span>
                    <span class="text-xs font-semibold text-slate-600">Ngày khác:</span>
                    <input type="date" min="{{ \Carbon\Carbon::today()->toDateString() }}" x-model="selectedDate" @change="changeDate()" class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <!-- Quick Date Chips (7 days) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                @php
                    $startDate = \Carbon\Carbon::today();
                @endphp
                @for($i = 0; $i < 7; $i++)
                    @php
                        $d = $startDate->copy()->addDays($i);
                        $dStr = $d->toDateString();
                        $dayLabel = $i === 0 ? 'Hôm nay' : ($i === 1 ? 'Ngày mai' : ($d->isWeekend() ? 'Cuối tuần' : 'Thứ ' . ($d->dayOfWeek + 1)));
                        $isSelected = ($selectedDate === $dStr);
                    @endphp
                    <button type="button" @click="setDate('{{ $dStr }}')" 
                        class="p-3.5 rounded-2xl border text-center transition-all duration-150 flex flex-col items-center justify-center cursor-pointer
                            {{ $isSelected 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25 border-blue-600 font-bold ring-2 ring-blue-300' 
                                : 'bg-white hover:bg-slate-50 border-slate-200 text-slate-700 shadow-xs hover:border-slate-300' }}">
                        <span class="text-[11px] font-semibold {{ $isSelected ? 'text-blue-100' : 'text-slate-500' }}">{{ $dayLabel }}</span>
                        <span class="text-xl font-extrabold font-headline my-0.5 {{ $isSelected ? 'text-white' : 'text-slate-900' }}">{{ $d->format('d') }}</span>
                        <span class="text-[10px] {{ $isSelected ? 'text-blue-100' : 'text-slate-400' }}">Tháng {{ $d->format('m') }}</span>
                    </button>
                @endfor
            </div>
        </div>

        <!-- Step 2: Select Court & Visual Time Slots -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Bước 2</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-headline mt-0.5">Chọn Khung Giờ &amp; Sân Thi Đấu</h2>
                    <p class="text-xs text-slate-500 mt-1">Bấm vào các ô giờ bên dưới để chọn sân. Có thể chọn nhiều giờ hoặc nhiều sân cùng lúc.</p>
                </div>

                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200/70">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded-md bg-white border-2 border-emerald-500 shadow-xs"></span>
                        <span class="text-slate-700">Còn trống</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded-md bg-amber-400 border border-amber-500 shadow-xs"></span>
                        <span class="text-amber-900">Giờ vàng (Peak)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded-md bg-blue-600 border border-blue-600 shadow-xs"></span>
                        <span class="text-blue-700 font-bold">Đang chọn</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded-md bg-slate-200 border border-slate-300"></span>
                        <span class="text-slate-400 line-through">Đã kín</span>
                    </div>
                </div>
            </div>

            <!-- Court Slots Grid by Court -->
            <div class="space-y-6">
                @forelse($courts as $court)
                    @php
                        $slots = $courtSlotsData[$court->id] ?? collect();
                    @endphp
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80">
                        <!-- Court Title Bar -->
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200/60">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-sm">
                                    {{ $court->court_number }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">{{ $court->name }}</h4>
                                    <span class="text-xs text-slate-500 font-medium">{{ $court->surface_type }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-100/80 px-3 py-1 rounded-full border border-emerald-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Sẵn sàng đón khách</span>
                            </span>
                        </div>

                        <!-- Slots Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2.5">
                            @foreach($slots as $slot)
                                @php
                                    $isUnavailable = !$slot['is_available'];
                                    $isPeak = $slot['is_peak'];
                                    $startTime = substr($slot['start_time'], 0, 5);
                                    $endTime = substr($slot['end_time'], 0, 5);
                                @endphp
                                <button type="button"
                                    @if($isUnavailable)
                                        disabled
                                    @else
                                        @click="toggleSlot({{ $court->id }}, '{{ $court->name }}', '{{ $slot['start_time'] }}', '{{ $slot['end_time'] }}', '{{ $slot['display_time'] }}', {{ $slot['price'] }})"
                                    @endif
                                    :class="isSlotSelected({{ $court->id }}, '{{ $slot['start_time'] }}') 
                                        ? '!bg-blue-600 !text-white !border-blue-600 !shadow-lg shadow-blue-500/25 ring-2 ring-blue-400 font-bold scale-[1.02]' 
                                        : ''"
                                    class="relative flex flex-col items-center justify-center py-2.5 px-2 rounded-xl border text-center transition-all duration-150 select-none cursor-pointer
                                        @if($isUnavailable)
                                            bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed opacity-65
                                        @elseif($isPeak)
                                            bg-amber-50/90 border-amber-300 text-amber-950 hover:bg-amber-100 hover:border-amber-400 shadow-xs
                                        @else
                                            bg-white border-slate-200 text-slate-800 hover:border-emerald-500 hover:bg-emerald-50/30 shadow-xs
                                        @endif
                                    ">
                                    
                                    @if($isPeak && !$isUnavailable)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-amber-500 text-white text-[9px] font-extrabold uppercase leading-none tracking-wider mb-1">
                                            Peak
                                        </span>
                                    @endif

                                    <span class="text-xs font-bold tracking-tight {{ $isUnavailable ? 'line-through' : '' }}">
                                        {{ $startTime }} - {{ $endTime }}
                                    </span>
                                    
                                    <span class="text-[11px] font-semibold mt-0.5 {{ $isUnavailable ? 'text-slate-400' : 'text-slate-500' }}"
                                          :class="isSlotSelected({{ $court->id }}, '{{ $slot['start_time'] }}') ? '!text-blue-100' : ''">
                                        @if($isUnavailable)
                                            Đã kín
                                        @else
                                            {{ number_format($slot['price'], 0, ',', '.') }}đ
                                        @endif
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-slate-300 p-8">
                        <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">sports_tennis</span>
                        <h4 class="text-base font-bold text-slate-700">Cụm sân chưa có sân con nào đang mở</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">Vui lòng chọn cơ sở khác hoặc liên hệ hotline để được hỗ trợ.</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 mt-4 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition">
                            <span class="material-symbols-outlined text-sm">travel_explore</span>
                            <span>Khám phá cụm sân khác</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Step 3: Notes & Confirm Bar -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md mb-8" x-show="selectedSlots.length > 0" x-transition>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Bước 3</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-headline mt-0.5 mb-3">Ghi Chú Đặt Sân</h2>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Ghi chú thêm cho sân (không bắt buộc):</label>
                <input type="text" name="notes" placeholder="Ví dụ: Đặt thêm 2 ống cầu Ba Sao, mượn 2 vợt tập..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
            </div>
        </div>

        <!-- Sticky Bottom Booking Summary Bar -->
        <div class="sticky bottom-4 z-40 bg-white/95 backdrop-blur-md border border-slate-200/90 shadow-2xl py-4 px-6 rounded-2xl max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4" x-show="selectedSlots.length > 0" x-transition>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Tóm tắt đặt sân:</span>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="font-bold text-slate-900 text-sm sm:text-base">
                        Đã chọn <span class="text-blue-600 font-extrabold" x-text="selectedSlots.length"></span> khung giờ ngày <span class="text-slate-800" x-text="formatDate(selectedDate)"></span>
                    </span>
                </div>
                <div class="flex flex-wrap gap-1.5 mt-1.5">
                    <template x-for="slot in selectedSlots" :key="slot.court_id + '_' + slot.start_time">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 flex items-center gap-1.5">
                            <span class="font-bold" x-text="slot.court_name"></span>
                            <span class="text-blue-400">•</span>
                            <span x-text="slot.display_time"></span>
                            <button type="button" @click="toggleSlot(slot.court_id, slot.court_name, slot.start_time, slot.end_time, slot.display_time, slot.price)" class="text-blue-400 hover:text-red-500 font-bold ml-1 text-sm leading-none">&times;</button>
                        </span>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-5 w-full sm:w-auto justify-between sm:justify-end">
                <div class="text-right">
                    <span class="text-xs text-slate-500 block font-medium">Tổng tiền (Thanh toán tại sân)</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-blue-600 font-headline" x-text="formatMoney(totalPrice)"></span>
                </div>

                @auth
                    <button type="submit" class="py-3.5 px-7 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition transform active:scale-98 flex items-center gap-2 shrink-0 cursor-pointer">
                        <span>Xác Nhận Đặt Sân</span>
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="py-3.5 px-7 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/25 transition transform active:scale-98 flex items-center gap-2 shrink-0">
                        <span>Đăng Nhập Để Đặt Sân</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                @endauth
            </div>
        </div>
    </form>

    <!-- Policies Card -->
    <div class="bg-blue-50/50 rounded-3xl p-6 sm:p-8 border border-blue-100 mt-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-xl">shield</span>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Chính Sách Đặt Sân GoSport</h3>
                <ul class="mt-2 space-y-1.5 text-xs text-slate-600 leading-relaxed list-disc list-inside">
                    <li><strong>Xác nhận ngay lập tức:</strong> Đơn đặt sân hợp lệ được tạo và xác nhận ngay, quý khách nhận mã đặt sân để xuất trình tại quầy.</li>
                    <li><strong>Hình thức thanh toán:</strong> Thanh toán trực tiếp tại sân khi đến thi đấu.</li>
                    <li><strong>Khóa slot chống trùng:</strong> Hệ thống tự động khóa khung giờ trong giao dịch đảm bảo không ai có thể đặt trùng sân của bạn.</li>
                    <li><strong>Hủy đơn linh hoạt:</strong> Quý khách có thể hủy đơn từ trang "Đơn đặt của tôi" nếu có thay đổi kế hoạch.</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
function bookingApp() {
    return {
        selectedDate: '{{ $selectedDate }}',
        selectedSlots: [],

        setDate(date) {
            this.selectedDate = date;
            window.location.href = "{{ route('venues.show', $venue->slug) }}?date=" + date;
        },

        changeDate() {
            window.location.href = "{{ route('venues.show', $venue->slug) }}?date=" + this.selectedDate;
        },

        toggleSlot(courtId, courtName, startTime, endTime, displayTime, price) {
            const index = this.selectedSlots.findIndex(s => s.court_id === courtId && s.start_time === startTime);
            if (index > -1) {
                this.selectedSlots.splice(index, 1);
            } else {
                this.selectedSlots.push({
                    court_id: courtId,
                    court_name: courtName,
                    start_time: startTime,
                    end_time: endTime,
                    display_time: displayTime,
                    price: price
                });
            }
        },

        isSlotSelected(courtId, startTime) {
            return this.selectedSlots.some(s => s.court_id === courtId && s.start_time === startTime);
        },

        get totalPrice() {
            return this.selectedSlots.reduce((acc, slot) => acc + slot.price, 0);
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        },

        formatDate(dateStr) {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return parts[2] + '/' + parts[1] + '/' + parts[0];
            }
            return dateStr;
        }
    }
}
</script>
@endsection
