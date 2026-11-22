@extends('layouts.admin')

@section('title', 'Cấu Hình Giờ Hoạt Động & Bảng Giá - GoSport Admin')
@section('page_title', 'Giờ Mở Cửa & Bảng Giá Theo Giờ')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Top Ribbon & Venue Selector -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="font-headline font-bold text-2xl text-slate-900">Giờ Mở Cửa &amp; Bảng Giá Cơ Bản</h1>
            <p class="text-sm text-slate-500">Cấu hình khung giờ hoạt động từng ngày trong tuần và đơn giá thuê sân theo khung giờ.</p>
        </div>

        <form method="GET" action="{{ route('admin.pricing.index') }}" class="flex items-center gap-2">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0">Chọn Cụm Sân:</label>
            <select name="venue_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-blue-600">
                @foreach($venues as $v)
                    <option value="{{ $v->id }}" {{ $venue && $venue->id === $v->id ? 'selected' : '' }}>
                        {{ $v->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($venue)
        <!-- 1. Operating Hours Section -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="mb-6 pb-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 font-headline">Cấu Hình Giờ Mở Cửa Từng Ngày</h2>
                    <p class="text-xs text-slate-500 mt-1">Hệ thống sẽ chỉ cho phép đặt các khung giờ nằm trong khoảng thời gian mở cửa này.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">schedule</span>
                    {{ $venue->name }}
                </span>
            </div>

            <form method="POST" action="{{ route('admin.pricing.operatingHours') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="venue_id" value="{{ $venue->id }}">

                @php
                    $dayNames = [
                        0 => 'Chủ Nhật',
                        1 => 'Thứ Hai',
                        2 => 'Thứ Ba',
                        3 => 'Thứ Tư',
                        4 => 'Thứ Năm',
                        5 => 'Thứ Sáu',
                        6 => 'Thứ Bảy',
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3">
                    @foreach($operatingHours as $index => $hour)
                        <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between space-y-2">
                            <input type="hidden" name="hours[{{ $index }}][id]" value="{{ $hour->id }}">
                            <div class="font-bold text-xs text-slate-800 flex items-center justify-between">
                                <span>{{ $dayNames[$hour->day_of_week] ?? "Ngày $hour->day_of_week" }}</span>
                                @if($hour->day_of_week == 0 || $hour->day_of_week == 6)
                                    <span class="text-[9px] px-1 bg-amber-100 text-amber-700 font-bold rounded">Cuối tuần</span>
                                @endif
                            </div>

                            <div>
                                <label class="text-[10px] text-slate-500 block">Mở cửa:</label>
                                <input type="time" name="hours[{{ $index }}][open_time]" value="{{ substr($hour->open_time, 0, 5) }}" required class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs">
                            </div>

                            <div>
                                <label class="text-[10px] text-slate-500 block">Đóng cửa:</label>
                                <input type="time" name="hours[{{ $index }}][close_time]" value="{{ substr($hour->close_time, 0, 5) }}" required class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs">
                            </div>

                            <label class="flex items-center gap-1.5 text-xs text-slate-600 pt-1 cursor-pointer">
                                <input type="checkbox" name="hours[{{ $index }}][is_closed]" value="1" {{ $hour->is_closed ? 'checked' : '' }} class="rounded text-rose-600 border-slate-300">
                                <span class="text-[11px]">Đóng cửa cả ngày</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                        Lưu Giờ Hoạt Động
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Pricing Rules Section -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8" x-data="{ addingRule: false }">
            <div class="mb-6 pb-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 font-headline">Bảng Giá Thuê Sân Theo Giờ</h2>
                    <p class="text-xs text-slate-500 mt-1">Cấu hình giá tiền mỗi giờ theo khung giờ thường, giờ vàng tối và ngày cuối tuần.</p>
                </div>

                <button @click="addingRule = !addingRule" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm shadow-emerald-500/20">
                    <span class="material-symbols-outlined text-base">add</span>
                    <span x-text="addingRule ? 'Đóng form' : 'Thêm Khung Giá Mới'">Thêm Khung Giá Mới</span>
                </button>
            </div>

            <!-- Add Rule Form Drawer -->
            <div x-show="addingRule" x-transition class="mb-6 p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200">
                <h3 class="text-sm font-bold text-emerald-900 mb-3">Thêm Khung Giá Mới Cho Cụm Sân</h3>
                <form method="POST" action="{{ route('admin.pricing.storeRule') }}" class="grid grid-cols-1 sm:grid-cols-6 gap-3 items-end">
                    @csrf
                    <input type="hidden" name="venue_id" value="{{ $venue->id }}">

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tên khung giờ *</label>
                        <input type="text" name="name" placeholder="Ví dụ: Giờ vàng cao điểm" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Áp dụng ngày *</label>
                        <select name="day_type" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-600">
                            <option value="weekday">Ngày thường (T2 - T6)</option>
                            <option value="weekend">Cuối tuần (T7 &amp; CN)</option>
                            <option value="all">Tất cả các ngày</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Từ giờ *</label>
                        <input type="time" name="start_time" value="17:00" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Đến giờ *</label>
                        <input type="time" name="end_time" value="21:00" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Giá / giờ (VND) *</label>
                        <input type="number" name="price_per_hour" value="200000" step="5000" min="0" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                    </div>

                    <div class="sm:col-span-6 flex items-center justify-between pt-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                            <input type="checkbox" name="is_peak" value="1" class="rounded text-amber-600 border-slate-300">
                            <span>Khung giờ cao điểm (Peak slot)</span>
                        </label>

                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            Lưu Khung Giá
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Rules Table -->
            <form method="POST" action="{{ route('admin.pricing.update') }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Tên Khung Giá</th>
                                <th class="px-4 py-3">Loại Ngày</th>
                                <th class="px-4 py-3">Khoảng Thời Gian</th>
                                <th class="px-4 py-3">Đơn Giá Cơ Bản (VND / giờ)</th>
                                <th class="px-4 py-3">Đặc Tính</th>
                                <th class="px-4 py-3 text-right">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($rules as $index => $r)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-4 py-3 font-bold text-slate-900">
                                        <input type="hidden" name="rules[{{ $index }}][id]" value="{{ $r->id }}">
                                        {{ $r->name }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($r->day_type === 'weekday')
                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold">Ngày thường</span>
                                        @elseif($r->day_type === 'weekend')
                                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold">Cuối tuần</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold">Tất cả các ngày</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-slate-800">
                                        {{ substr($r->start_time, 0, 5) }} - {{ substr($r->end_time, 0, 5) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1.5 max-w-[200px]">
                                            <input type="number" name="rules[{{ $index }}][price_per_hour]" value="{{ (int)$r->price_per_hour }}" step="5000" min="0" required class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-900 focus:outline-none focus:border-blue-600">
                                            <span class="text-xs text-slate-400 font-bold shrink-0">đ/h</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($r->is_peak)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[11px]">
                                                <span class="material-symbols-outlined text-xs">local_fire_department</span> Giờ vàng
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">Giờ tiêu chuẩn</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" onclick="if(confirm('Bạn có chắc muốn xóa khung giá này?')) { document.getElementById('delete-rule-{{ $r->id }}').submit(); }" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Xóa khung giá">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                                        Chưa có khung giá nào cho cụm sân này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($rules->isNotEmpty())
                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                            Cập Nhật Bảng Giá
                        </button>
                    </div>
                @endif
            </form>

            @foreach($rules as $r)
                <form id="delete-rule-{{ $r->id }}" method="POST" action="{{ route('admin.pricing.destroyRule', $r->id) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center text-slate-500 bg-white rounded-2xl border border-slate-200">
            Chưa có cụm sân nào trong hệ thống. <a href="{{ route('admin.venues.create') }}" class="text-blue-600 font-bold hover:underline">Tạo cụm sân đầu tiên</a>
        </div>
    @endif
</div>
@endsection
