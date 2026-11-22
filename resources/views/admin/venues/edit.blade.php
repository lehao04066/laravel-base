@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Cụm Sân & Sân Con - GoSport Admin')
@section('page_title', 'Chỉnh Sửa: ' . $venue->name)

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Top Action Ribbon -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.venues.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            <span>Quay lại danh sách cụm sân</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('venues.show', $venue->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition shadow-xs">
                <span class="material-symbols-outlined text-base text-blue-600">visibility</span>
                <span>Xem Trang Khách</span>
            </a>

            <form method="POST" action="{{ route('admin.venues.toggle', $venue->id) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ $venue->is_active ? 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' }}">
                    <span class="material-symbols-outlined text-base">{{ $venue->is_active ? 'pause_circle' : 'play_circle' }}</span>
                    <span>{{ $venue->is_active ? 'Ngừng hoạt động' : 'Kích hoạt lại' }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- 1. Venue Details Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <div class="mb-6 pb-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900 font-headline">Thông Tin Cụm Sân</h2>
                <p class="text-xs text-slate-500 mt-1">Cập nhật thông tin địa chỉ, liên hệ và thời gian hoạt động của cụm sân.</p>
            </div>
            <div>
                @if($venue->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đang hoạt động
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-bold text-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Ngừng hoạt động
                    </span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.venues.update', $venue->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tên cụm sân *</label>
                    <input type="text" name="name" value="{{ old('name', $venue->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Địa chỉ chi tiết *</label>
                    <input type="text" name="address" value="{{ old('address', $venue->address) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Quận / Huyện *</label>
                    <input type="text" name="district" value="{{ old('district', $venue->district) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tỉnh / Thành phố</label>
                    <input type="text" name="city" value="{{ old('city', $venue->city) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Số điện thoại *</label>
                    <input type="text" name="phone" value="{{ old('phone', $venue->phone) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email liên hệ</label>
                    <input type="email" name="email" value="{{ old('email', $venue->email) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Giờ mở cửa mặc định *</label>
                    <input type="time" name="opening_time" value="{{ old('opening_time', substr($venue->opening_time, 0, 5)) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Giờ đóng cửa mặc định *</label>
                    <input type="time" name="closing_time" value="{{ old('closing_time', substr($venue->closing_time, 0, 5)) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Link ảnh đại diện (URL)</label>
                    <input type="url" name="featured_image" value="{{ old('featured_image', $venue->featured_image) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mô tả cụm sân</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">{{ old('description', $venue->description) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                    Lưu Thay Đổi Cụm Sân
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Child Courts Management -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8" x-data="{ addingCourt: false }">
        <div class="mb-6 pb-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 font-headline">Hệ Thống Sân Con (Child Courts)</h2>
                <p class="text-xs text-slate-500 mt-1">Quản lý từng sân con độc lập trong cụm sân: đặt tên, đổi số sân, và ngừng/kích hoạt hoạt động.</p>
            </div>

            <button @click="addingCourt = !addingCourt" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm shadow-emerald-500/20">
                <span class="material-symbols-outlined text-base">add</span>
                <span x-text="addingCourt ? 'Đóng form' : 'Thêm Sân Con'">Thêm Sân Con</span>
            </button>
        </div>

        <!-- Add Child Court Form Drawer -->
        <div x-show="addingCourt" x-transition class="mb-6 p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200">
            <h3 class="text-sm font-bold text-emerald-900 mb-3">Thêm Sân Con Mới</h3>
            <form method="POST" action="{{ route('admin.courts.store', $venue->id) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Số thứ tự sân *</label>
                    <input type="number" name="court_number" value="{{ ($venue->courts->max('court_number') ?? 0) + 1 }}" required min="1" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tên sân con *</label>
                    <input type="text" name="name" placeholder="Ví dụ: Sân 9 - Center Court" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Loại thảm sân</label>
                    <input type="text" name="surface_type" value="Yonex BWF Standard" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        Lưu Sân Con
                    </button>
                </div>
            </form>
        </div>

        <!-- Courts Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Số Sân</th>
                        <th class="px-4 py-3">Tên Sân Con</th>
                        <th class="px-4 py-3">Mặt Thảm</th>
                        <th class="px-4 py-3">Trạng Thái</th>
                        <th class="px-4 py-3 text-right">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($venue->courts->sortBy('court_number') as $c)
                        <tr class="hover:bg-slate-50/60 transition" x-data="{ editing: false }">
                            <td class="px-4 py-3 font-bold text-slate-900">
                                Sân {{ $c->court_number }}
                            </td>
                            <td class="px-4 py-3">
                                <div x-show="!editing">
                                    <div class="font-bold text-slate-900">{{ $c->name }}</div>
                                </div>
                                <div x-show="editing" class="space-y-1">
                                    <form id="edit-court-{{ $c->id }}" method="POST" action="{{ route('admin.courts.update', $c->id) }}" class="flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="court_number" value="{{ $c->court_number }}" class="w-16 px-2 py-1 bg-white border border-slate-300 rounded text-xs" title="Số thứ tự">
                                        <input type="text" name="name" value="{{ $c->name }}" required class="px-2 py-1 bg-white border border-slate-300 rounded text-xs">
                                        <input type="text" name="surface_type" value="{{ $c->surface_type }}" class="px-2 py-1 bg-white border border-slate-300 rounded text-xs">
                                        <button type="submit" class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-bold">Lưu</button>
                                        <button type="button" @click="editing = false" class="px-2 py-1 bg-slate-200 text-slate-700 rounded text-xs">Hủy</button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ $c->surface_type }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if($c->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ngừng hoạt động
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="editing = !editing" class="p-1.5 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-slate-100 transition" title="Sửa thông tin sân">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </button>

                                    <form method="POST" action="{{ route('admin.courts.toggle', $c->id) }}">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg transition {{ $c->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }}" title="{{ $c->is_active ? 'Ngừng hoạt động sân' : 'Kích hoạt lại sân' }}">
                                            <span class="material-symbols-outlined text-base">{{ $c->is_active ? 'pause_circle' : 'play_circle' }}</span>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.courts.destroy', $c->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa sân con này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Xóa sân">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">
                                Cụm sân này hiện chưa có sân con nào. Vui lòng bấm "Thêm Sân Con" để tạo mới.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
