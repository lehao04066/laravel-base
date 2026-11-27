@extends('layouts.admin')

@section('title', 'Thêm Cụm Sân Mới - GoSport Admin')
@section('page_title', 'Thêm Cụm Sân Mới')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.venues.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            <span>Quay lại danh sách</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <div class="mb-6 pb-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-900 font-headline">Thông Tin Cụm Sân Mới</h2>
            <p class="text-xs text-slate-500 mt-1">Điền đầy đủ thông tin để khởi tạo cụm sân và tự động sinh các sân con ban đầu.</p>
        </div>

        <form method="POST" action="{{ route('admin.venues.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tên cụm sân *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: GoSport Arena Hoàng Mai" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Địa chỉ chi tiết *</label>
                    <input type="text" name="address" value="{{ old('address') }}" required placeholder="Số 18 Định Công, Hoàng Mai" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Quận / Huyện *</label>
                    <input type="text" name="district" value="{{ old('district') }}" required placeholder="Hoàng Mai" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tỉnh / Thành phố</label>
                    <input type="text" name="city" value="{{ old('city', 'Hà Nội') }}" placeholder="Hà Nội" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Số điện thoại *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="024 3888 9999" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email liên hệ</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="arena@gosport.vn" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Giờ mở cửa *</label>
                    <input type="time" name="opening_time" value="{{ old('opening_time', '06:00') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Giờ đóng cửa *</label>
                    <input type="time" name="closing_time" value="{{ old('closing_time', '23:00') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Số sân con khởi tạo ban đầu</label>
                    <input type="number" name="initial_courts_count" value="{{ old('initial_courts_count', 4) }}" min="1" max="30" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Link ảnh đại diện (URL)</label>
                    <input type="url" name="featured_image" value="{{ old('featured_image', 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mô tả cụm sân</label>
                    <textarea name="description" rows="3" placeholder="Giới thiệu về tiện ích, thảm sân và trang thiết bị..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.venues.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                    Tạo Cụm Sân Mới
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
