@extends('layouts.admin')

@section('title', 'Quản Lý Cụm Sân & Sân Con - GoSport Admin')
@section('page_title', 'Quản Lý Cụm Sân & Sân Con')

@section('content')
<div class="space-y-6">
    <!-- Top Action Ribbon -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="font-headline font-bold text-2xl text-slate-900">Danh Sách Cụm Sân</h1>
            <p class="text-sm text-slate-500">Quản lý các cụm sân thể thao, hệ thống sân con, giờ hoạt động và trạng thái sẵn sàng.</p>
        </div>

        <a href="{{ route('admin.venues.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
            <span class="material-symbols-outlined text-lg">add_circle</span>
            <span>Thêm Cụm Sân Mới</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.venues.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm theo tên, địa chỉ hoặc quận..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
            </div>

            <div class="sm:col-span-4">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                    Lọc
                </button>
                @if(request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.venues.index') }}" class="p-2 text-slate-400 hover:text-slate-600 flex items-center justify-center rounded-xl border border-slate-200">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Venues Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Cụm Sân</th>
                        <th class="px-6 py-4">Địa Chỉ</th>
                        <th class="px-6 py-4">Giờ Hoạt Động</th>
                        <th class="px-6 py-4">Số Sân Con</th>
                        <th class="px-6 py-4">Trạng Thái</th>
                        <th class="px-6 py-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($venues as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $v->featured_image ?? 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=100&auto=format&fit=crop&q=80' }}" alt="{{ $v->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0">
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $v->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $v->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div>{{ $v->address }}</div>
                                <div class="text-slate-400">{{ $v->district }}, {{ $v->city }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 font-semibold text-slate-700">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    {{ substr($v->opening_time, 0, 5) }} - {{ substr($v->closing_time, 0, 5) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-900">
                                <span class="inline-block px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700">
                                    {{ $v->courts_count }} sân
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($v->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Đang hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Ngừng hoạt động
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.venues.edit', $v->id) }}" class="p-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition" title="Chỉnh sửa & Quản lý sân con">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>

                                    <form method="POST" action="{{ route('admin.venues.toggle', $v->id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái cụm sân này?')">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl {{ $v->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition" title="{{ $v->is_active ? 'Ngừng hoạt động' : 'Kích hoạt lại' }}">
                                            <span class="material-symbols-outlined text-lg">{{ $v->is_active ? 'pause_circle' : 'play_circle' }}</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('venues.show', $v->slug) }}" target="_blank" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Xem trên trang khách">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Chưa có cụm sân nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($venues->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $venues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
