@extends('layouts.main')

@section('title', 'Ưu Đãi & Voucher GoSport')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500 via-orange-500 to-rose-600 p-8 text-white shadow-xl sm:p-10">
        <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full border-[28px] border-white/10"></div>
        <div class="relative z-10 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <span class="mb-3 inline-flex items-center gap-1 rounded-full bg-white/20 px-3 py-1 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">stars</span>
                    Chương trình ưu đãi GoSport
                </span>
                <h1 class="font-headline text-2xl font-extrabold sm:text-4xl">Voucher Đặt Sân & Giờ Vàng</h1>
                <p class="mt-2 max-w-xl text-xs leading-relaxed text-white/90 sm:text-sm">
                    Chọn mã phù hợp, nhập vào bước đặt sân và hệ thống sẽ tự động kiểm tra điều kiện, giới hạn sử dụng và số tiền được giảm.
                </p>
            </div>
            <a href="{{ route('wallet.show') }}" class="shrink-0 rounded-xl bg-white px-6 py-3.5 text-sm font-bold text-orange-600 shadow-lg transition hover:bg-orange-50">
                Nạp tiền vào ví
            </a>
        </div>
    </section>

    <section class="mt-12">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Kho mã khuyến mãi</span>
                <h2 class="mt-1 font-headline text-2xl font-extrabold text-slate-900">Ưu đãi đang khả dụng</h2>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ $vouchers->count() }} mã đang mở</span>
        </div>

        @if($vouchers->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-slate-300">local_offer</span>
                <h3 class="mt-3 text-lg font-bold text-slate-800">Chưa có voucher khả dụng</h3>
                <p class="mt-1 text-xs text-slate-500">Hãy quay lại sau để nhận các ưu đãi mới nhất từ GoSport.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($vouchers as $voucher)
                    <article class="relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50"></div>
                        <div class="relative z-10">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full border border-blue-200 bg-blue-100 px-3 py-1 font-mono text-xs font-bold text-blue-800">{{ $voucher->code }}</span>
                                <span class="text-[11px] font-semibold text-slate-400">HSD: {{ $voucher->end_date->format('d/m/Y') }}</span>
                            </div>
                            <h3 class="font-headline text-lg font-bold text-slate-900">{{ $voucher->title }}</h3>
                            <p class="mb-4 mt-1 text-xs text-slate-500">Đơn tối thiểu: {{ number_format((float) $voucher->min_order_amount, 0, ',', '.') }}đ</p>
                            <div class="mb-5 flex items-center justify-between rounded-xl border border-slate-200/60 bg-slate-50 p-3 text-xs">
                                <span class="text-slate-600">Mức giảm</span>
                                <strong class="font-headline text-base font-bold text-emerald-600">
                                    -{{ number_format((float) $voucher->discount_value, 0, ',', '.') }}{{ $voucher->discount_type === 'percent' ? '%' : 'đ' }}
                                </strong>
                            </div>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('promotions.save', $voucher->id) }}" class="relative z-10">
                                @csrf
                                <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-slate-900 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-slate-800">
                                    <span class="material-symbols-outlined text-base">content_copy</span>
                                    Lưu mã vào ví ưu đãi
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="relative z-10 block w-full rounded-xl bg-blue-600 py-2.5 text-center text-xs font-bold text-white shadow-md transition hover:bg-blue-700">
                                Đăng nhập để lưu mã
                            </a>
                        @endauth
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="mt-12 rounded-3xl border border-blue-100 bg-blue-50/60 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-sm">
                <span class="material-symbols-outlined">verified</span>
            </div>
            <div>
                <h2 class="font-headline text-lg font-bold text-slate-900">Cách sử dụng voucher</h2>
                <ol class="mt-3 space-y-2 text-xs leading-relaxed text-slate-600">
                    <li><strong>1.</strong> Chọn mã phù hợp với giá trị đơn đặt sân.</li>
                    <li><strong>2.</strong> Nhập mã tại bước Ghi chú & Mã khuyến mãi khi chọn sân.</li>
                    <li><strong>3.</strong> Hệ thống tự kiểm tra hạn dùng, đơn tối thiểu và lượt sử dụng trước khi xác nhận.</li>
                </ol>
            </div>
        </div>
    </section>
</div>
@endsection
