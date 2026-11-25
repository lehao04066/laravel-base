@extends('layouts.main')

@section('title', 'Hồ sơ & Ví GoSport')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ topupModal: false }">
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-6 text-white shadow-xl sm:p-10">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative z-10 grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-300/20 bg-white/10 px-3 py-1.5 text-xs font-bold text-blue-100">
                    <span class="material-symbols-outlined text-base">sports_tennis</span>
                    Hồ sơ thành viên GoSport
                </span>
                <h1 class="mt-4 font-headline text-3xl font-extrabold sm:text-4xl">{{ $user->name }}</h1>
                <p class="mt-2 text-sm text-blue-100">{{ $user->email }} <span class="px-1">·</span> {{ $user->phone }}</p>
                <div class="mt-5 flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-blue-50">Trình độ: {{ str_replace('_', ' ', $profile->skill_level) }}</span>
                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-blue-50">Phong cách: {{ $profile->play_style ?: 'Chưa cập nhật' }}</span>
                </div>
            </div>

            <div class="min-w-64 rounded-2xl border border-white/10 bg-white/10 p-6 text-center backdrop-blur-md sm:min-w-80">
                <span class="text-xs font-semibold uppercase tracking-wider text-blue-100">Số dư ví GoSport Pay</span>
                <p class="mt-2 font-headline text-4xl font-black text-amber-300">
                    {{ number_format((float) $wallet->balance, 0, ',', '.') }}<span class="ml-1 text-2xl">đ</span>
                </p>
                <p class="mt-1 text-xs text-blue-100">{{ $wallet->currency }} · {{ $wallet->status === 'active' ? 'Đang hoạt động' : 'Tạm khóa' }}</p>
                <button type="button" @click="topupModal = true" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-amber-400 px-5 py-2.5 text-xs font-extrabold text-slate-950 shadow-md transition hover:bg-amber-300">
                    <span class="material-symbols-outlined text-base">add</span>
                    Nạp tiền (+15%)
                </button>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-12">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-5">
            <div class="mb-5 flex items-center gap-3">
                <div class="grid h-11 w-11 place-items-center rounded-2xl bg-blue-50 text-blue-600">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <div>
                    <h2 class="font-headline text-lg font-bold text-slate-900">Ví thể thao</h2>
                    <p class="text-xs text-slate-500">Ví điện tử GoSport gắn với tài khoản của bạn</p>
                </div>
            </div>

            <dl class="space-y-4 text-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <dt class="text-slate-500">Số dư khả dụng</dt>
                    <dd class="font-bold text-slate-900">{{ number_format((float) $wallet->balance, 0, ',', '.') }}đ</dd>
                </div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <dt class="text-slate-500">Số dư đang giữ</dt>
                    <dd class="font-semibold text-slate-700">{{ number_format((float) $wallet->frozen_balance, 0, ',', '.') }}đ</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-500">Trạng thái</dt>
                    <dd class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">{{ $wallet->status === 'active' ? 'Đang hoạt động' : 'Tạm khóa' }}</dd>
                </div>
            </dl>

            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs leading-relaxed text-amber-900">
                Nạp tiền sẽ cộng số dư trực tiếp vào ví và ghi lại lịch sử giao dịch. Chưa kết nối cổng thanh toán thật.
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-7">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-headline text-lg font-bold text-slate-900">Biến động số dư ví</h2>
                    <p class="mt-1 text-xs text-slate-500">Các giao dịch gần đây trên ví GoSport Pay</p>
                </div>
                <span class="material-symbols-outlined text-blue-600">history</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($ledgerEntries as $entry)
                    <div class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-800">{{ $entry->description ?: 'Giao dịch ví' }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $entry->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="font-headline text-sm font-extrabold {{ $entry->amount >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $entry->amount > 0 ? '+' : '' }}{{ number_format((float) $entry->amount, 0, ',', '.') }}đ
                            </p>
                            <p class="mt-1 text-[11px] text-slate-400">Số dư {{ number_format((float) $entry->balance_after, 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 px-4 py-8 text-center">
                        <span class="material-symbols-outlined text-3xl text-slate-300">receipt_long</span>
                        <p class="mt-2 text-sm font-semibold text-slate-600">Ví chưa có giao dịch</p>
                        <p class="mt-1 text-xs text-slate-400">Lịch sử nạp và sử dụng ví sẽ hiển thị tại đây.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-5 flex items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-lg font-bold text-slate-900">Mã ưu đãi đã lưu</h2>
                <p class="mt-1 text-xs text-slate-500">Các voucher bạn đã lưu từ Trung tâm Ưu đãi.</p>
            </div>
            <a href="{{ route('promotions.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:underline">
                Xem thêm
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            @forelse ($savedVouchers as $voucher)
                <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm font-bold text-slate-900">{{ $voucher->code }}</span>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Đã lưu</span>
                        </div>
                        <p class="mt-1 truncate text-xs text-slate-500">{{ $voucher->title }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">HSD: {{ $voucher->end_date->format('d/m/Y') }}</p>
                    </div>
                    <span class="shrink-0 rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-extrabold text-amber-800">
                        -{{ number_format((float) $voucher->discount_value, 0, ',', '.') }}{{ $voucher->discount_type === 'percent' ? '%' : 'đ' }}
                    </span>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center md:col-span-2">
                    <span class="material-symbols-outlined text-3xl text-slate-300">local_offer</span>
                    <p class="mt-2 text-sm font-semibold text-slate-600">Bạn chưa lưu mã ưu đãi nào</p>
                    <a href="{{ route('promotions.index') }}" class="mt-3 inline-flex rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700">Khám phá voucher</a>
                </div>
            @endforelse
        </div>
    </section>

    <div x-show="topupModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm">
        <div class="relative w-full max-w-sm rounded-3xl bg-white p-8 text-center shadow-2xl" @click.outside="topupModal = false">
            <button type="button" @click="topupModal = false" class="absolute right-4 top-4 rounded-lg p-2 text-slate-400 hover:text-slate-700" aria-label="Đóng">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="mx-auto mb-3 grid h-12 w-12 place-items-center rounded-2xl bg-amber-50 text-amber-600">
                <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
            </div>
            <h2 class="font-headline text-xl font-bold text-slate-900">Nạp tiền vào ví</h2>
            <p class="mt-2 text-xs leading-relaxed text-slate-500">Chọn mức nạp. Ví demo sẽ được cộng thêm 15% thưởng ngay lập tức.</p>

            <form method="POST" action="{{ route('wallet.topup') }}" class="my-5 space-y-3 text-left">
                @csrf
                <label for="amount" class="block text-xs font-bold text-slate-700">Mức nạp</label>
                <select id="amount" name="amount" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800">
                    <option value="200000">200.000đ (+30.000đ thưởng)</option>
                    <option value="500000">500.000đ (+75.000đ thưởng)</option>
                    <option value="1000000">1.000.000đ (+150.000đ thưởng)</option>
                    <option value="2000000">2.000.000đ (+300.000đ thưởng)</option>
                </select>
                <button type="submit" class="w-full rounded-xl bg-amber-400 py-3 text-xs font-extrabold text-slate-950 shadow-md transition hover:bg-amber-300">
                    Xác nhận nạp tiền
                </button>
            </form>
            <p class="text-[11px] leading-relaxed text-slate-400">Đây là thao tác mô phỏng, chưa phát sinh thanh toán thật.</p>
        </div>
    </div>
</div>
@endsection
