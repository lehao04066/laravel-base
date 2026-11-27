@php
    $dashboard = match ($dashboardType) {
        'owner' => [
            'title' => 'Bảng điều hành chủ sân',
            'eyebrow' => 'OWNER SUITE',
            'intro' => 'Theo dõi hoạt động cụm sân và các chỉ số vận hành tại một nơi.',
            'icon' => 'storefront',
            'panel' => 'Tổng quan vận hành',
            'empty' => 'Chưa có dữ liệu hoạt động',
            'emptyText' => 'Thông tin đặt sân, doanh thu và trạng thái sân sẽ hiển thị khi dữ liệu vận hành được kết nối.',
            'stats' => [
                ['label' => 'Doanh thu tháng', 'value' => '—', 'hint' => 'Chờ dữ liệu giao dịch', 'icon' => 'payments'],
                ['label' => 'Công suất sân', 'value' => '—', 'hint' => 'Chờ dữ liệu lịch sân', 'icon' => 'monitoring'],
                ['label' => 'Lượt đặt hôm nay', 'value' => '—', 'hint' => 'Chưa có thống kê', 'icon' => 'event_available'],
                ['label' => 'Cụm sân quản lý', 'value' => '—', 'hint' => 'Chưa có cụm sân được gán', 'icon' => 'location_city'],
            ],
        ],
        'coach' => [
            'title' => 'Đội ngũ huấn luyện viên',
            'eyebrow' => 'COACH DIRECTORY',
            'intro' => 'Danh sách huấn luyện viên GoSport cùng số lượng và thông tin chuyên môn.',
            'icon' => 'sports_tennis',
            'stats' => [
                ['label' => 'Tổng số HLV', 'value' => number_format($coachCount), 'hint' => 'Huấn luyện viên trong hệ thống', 'icon' => 'groups'],
                ['label' => 'Học viên', 'value' => $coachStudentCount, 'hint' => 'Tổng học viên đang theo học', 'icon' => 'school'],
                ['label' => 'Đánh giá trung bình', 'value' => $coachAverageRating, 'hint' => 'Trên thang điểm 5', 'icon' => 'star'],
                ['label' => 'Mức phí trung bình', 'value' => $coachAverageHourlyRate === '—' ? '—' : $coachAverageHourlyRate.'đ', 'hint' => 'Mỗi giờ huấn luyện', 'icon' => 'payments'],
            ],
        ],
        default => [
            'title' => 'Bàn làm việc nhân viên',
            'eyebrow' => 'FRONT DESK',
            'intro' => 'Không gian hỗ trợ khách hàng và điều phối công việc tại sân.',
            'icon' => 'support_agent',
            'panel' => 'Điều phối trong ngày',
            'empty' => 'Chưa có lịch công việc',
            'emptyText' => 'Lịch sân, check-in và các yêu cầu hỗ trợ sẽ xuất hiện tại đây khi được phân công.',
            'stats' => [
                ['label' => 'Lịch cần xử lý', 'value' => '—', 'hint' => 'Chưa có dữ liệu lịch sân', 'icon' => 'event_note'],
                ['label' => 'Check-in hôm nay', 'value' => '—', 'hint' => 'Chưa có dữ liệu check-in', 'icon' => 'qr_code_scanner'],
                ['label' => 'Ca làm hiện tại', 'value' => '—', 'hint' => 'Chưa có ca được gán', 'icon' => 'schedule'],
                ['label' => 'Yêu cầu hỗ trợ', 'value' => '—', 'hint' => 'Chưa có yêu cầu mới', 'icon' => 'support'],
            ],
        ],
    };
@endphp
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $dashboard['title'] }} | GoSport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        .portal { --accent:#2563eb; --accent-soft:#eff6ff; --page:#f1f5f9; --surface:#fff; --border:#e2e8f0; --text:#0f172a; --muted:#64748b; --subtle:#94a3b8; min-height:100vh; background:var(--page); color:var(--text); }
        .portal[data-role="owner"] { --accent:#f59e0b; --accent-soft:rgba(245,158,11,.12); --page:#0b0f19; --surface:#0f172a; --border:#1e293b; --text:#f8fafc; --muted:#94a3b8; --subtle:#64748b; }
        .portal[data-role="coach"] { --accent:#7c3aed; --accent-soft:#f5f3ff; --page:#f5f3ff; --surface:#fff; --border:#e9e2f8; --text:#1e1b2e; --muted:#6b6680; --subtle:#9690a8; }
        .portal[data-role="staff"] { --accent:#059669; --accent-soft:#ecfdf5; --page:#f1f5f9; --surface:#fff; --border:#e2e8f0; --text:#0f172a; --muted:#64748b; --subtle:#94a3b8; }
        .topbar { position:sticky; top:0; z-index:5; border-bottom:1px solid var(--border); background:color-mix(in srgb, var(--surface) 92%, transparent); backdrop-filter:blur(14px); }
        .topbar-inner,.content { width:min(1180px,calc(100% - 40px)); margin:0 auto; }
        .topbar-inner { min-height:76px; display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .brand { display:flex; align-items:center; gap:12px; color:var(--text); text-decoration:none; }
        .brand-mark { width:42px; height:42px; display:grid; place-items:center; border-radius:14px; background:var(--accent); color:#fff; box-shadow:0 8px 22px color-mix(in srgb, var(--accent) 25%, transparent); }
        [data-role="owner"] .brand-mark { color:#111827; }
        .brand-name { font:700 20px 'Space Grotesk',sans-serif; letter-spacing:-.6px; }
        .brand-name span { color:var(--accent); }
        .brand-caption { display:block; margin-top:2px; color:var(--muted); font-size:9px; font-weight:800; letter-spacing:1.3px; }
        .top-actions { display:flex; align-items:center; gap:12px; }
        .identity { text-align:right; }
        .identity strong { display:block; color:var(--text); font-size:12px; }
        .identity span { display:block; margin-top:3px; color:var(--muted); font-size:10px; }
        .home-link,.logout { min-height:38px; display:inline-flex; align-items:center; justify-content:center; gap:7px; padding:0 12px; border:1px solid var(--border); border-radius:11px; background:var(--surface); color:var(--text); font:700 11px 'Plus Jakarta Sans',sans-serif; text-decoration:none; cursor:pointer; }
        .home-link:hover,.logout:hover { border-color:var(--accent); color:var(--accent); }
        .logout { background:transparent; }
        .content { padding:34px 0 56px; }
        .notice { margin-bottom:20px; padding:13px 16px; border:1px solid #a7f3d0; border-radius:14px; background:#ecfdf5; color:#047857; font-size:12px; font-weight:700; }
        .intro { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
        .eyebrow { color:var(--accent); font-size:10px; font-weight:800; letter-spacing:1.6px; }
        h1 { margin:7px 0 8px; color:var(--text); font:700 clamp(24px,3vw,34px) 'Space Grotesk',sans-serif; letter-spacing:-1px; }
        .intro p { margin:0; max-width:650px; color:var(--muted); font-size:13px; line-height:1.7; }
        .date-pill { flex:none; display:inline-flex; align-items:center; gap:7px; padding:10px 13px; border:1px solid var(--border); border-radius:12px; background:var(--surface); color:var(--muted); font-size:11px; font-weight:700; }
        .stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:15px; margin-bottom:20px; }
        .stat,.panel { border:1px solid var(--border); border-radius:20px; background:var(--surface); box-shadow:0 8px 28px rgba(15,23,42,.035); }
        [data-role="owner"] .stat,[data-role="owner"] .panel { box-shadow:0 12px 35px rgba(0,0,0,.18); }
        .stat { padding:19px; }
        .stat-top { display:flex; align-items:center; justify-content:space-between; gap:10px; }
        .stat-label { color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.5px; text-transform:uppercase; }
        .stat-icon { width:35px; height:35px; display:grid; place-items:center; border-radius:11px; background:var(--accent-soft); color:var(--accent); }
        .stat-value { margin-top:15px; color:var(--text); font:700 27px 'Space Grotesk',sans-serif; }
        .stat-hint { margin-top:5px; color:var(--subtle); font-size:10px; line-height:1.5; }
        .work-grid { display:grid; grid-template-columns:minmax(0,1.55fr) minmax(260px,.85fr); gap:18px; }
        .panel { padding:23px; }
        .panel-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:19px; }
        .panel-head h2 { margin:0; color:var(--text); font:700 16px 'Space Grotesk',sans-serif; }
        .panel-head span { color:var(--muted); font-size:10px; }
        .empty-state { min-height:210px; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:20px; border:1px dashed var(--border); border-radius:16px; text-align:center; }
        .empty-icon { width:50px; height:50px; display:grid; place-items:center; border-radius:16px; background:var(--accent-soft); color:var(--accent); }
        .empty-state strong { margin-top:13px; color:var(--text); font-size:12px; }
        .empty-state p { max-width:420px; margin:7px 0 0; color:var(--muted); font-size:11px; line-height:1.7; }
        .side-list { display:grid; gap:11px; }
        .side-item { display:flex; align-items:flex-start; gap:12px; padding:13px; border:1px solid var(--border); border-radius:14px; background:color-mix(in srgb, var(--surface) 90%, var(--page)); }
        .side-item .material-symbols-outlined { color:var(--accent); }
        .side-item strong { display:block; color:var(--text); font-size:11px; }
        .side-item span:last-child { display:block; margin-top:4px; color:var(--muted); font-size:10px; line-height:1.5; }
        .status-chip { display:inline-flex; align-items:center; gap:6px; padding:7px 10px; border:1px solid var(--border); border-radius:999px; background:var(--accent-soft); color:var(--accent); font-size:9px; font-weight:800; letter-spacing:.4px; text-transform:uppercase; }
        .status-dot { width:6px; height:6px; border-radius:50%; background:var(--accent); }
        .coach-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:15px; }
        .coach-card { overflow:hidden; border:1px solid var(--border); border-radius:17px; background:var(--surface); }
        .coach-photo { position:relative; height:150px; display:flex; align-items:center; justify-content:center; overflow:hidden; background:linear-gradient(135deg,var(--accent-soft),var(--page)); color:var(--accent); }
        .coach-photo img { width:100%; height:100%; object-fit:cover; }
        .coach-photo .material-symbols-outlined { font-size:50px; }
        .coach-rating { position:absolute; right:10px; top:10px; display:flex; align-items:center; gap:4px; padding:6px 9px; border-radius:999px; background:rgba(15,23,42,.78); color:#fff; font-size:10px; font-weight:800; }
        .coach-rating .material-symbols-outlined { color:#fbbf24; font-size:14px; }
        .coach-info { padding:15px; }
        .coach-info h3 { margin:0; color:var(--text); font:700 15px 'Space Grotesk',sans-serif; }
        .coach-title { min-height:32px; margin:5px 0 0; color:var(--accent); font-size:10px; font-weight:700; line-height:1.55; }
        .coach-bio { display:-webkit-box; min-height:48px; margin:9px 0 13px; overflow:hidden; color:var(--muted); font-size:10px; line-height:1.6; -webkit-box-orient:vertical; -webkit-line-clamp:3; }
        .coach-meta { display:flex; align-items:center; justify-content:space-between; gap:8px; padding-top:11px; border-top:1px solid var(--border); color:var(--muted); font-size:9px; }
        .coach-meta strong { color:var(--text); font-size:10px; }
        @media (max-width:850px) { .stats { grid-template-columns:repeat(2,minmax(0,1fr)); } .work-grid { grid-template-columns:1fr; } }
        @media (max-width:560px) { .topbar-inner,.content { width:min(100% - 28px,1180px); } .topbar-inner { min-height:68px; } .identity,.home-link { display:none; } .intro { align-items:flex-start; flex-direction:column; } .stats { gap:10px; } .stat { padding:15px; } .stat-label { font-size:9px; } .stat-value { font-size:23px; } .panel { padding:18px; } .coach-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="portal" data-role="{{ $dashboardType }}">
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark material-symbols-outlined">{{ $dashboard['icon'] }}</span>
                <span><span class="brand-name">Go<span>Sport</span></span><span class="brand-caption">{{ $dashboard['eyebrow'] }}</span></span>
            </a>
            <div class="top-actions">
                <div class="identity"><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }} · {{ $roleLabel }}</span></div>
                <a class="home-link" href="{{ route('home') }}"><span class="material-symbols-outlined" style="font-size:17px">language</span> Website GoSport</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit"><span class="material-symbols-outlined" style="font-size:17px">logout</span><span class="logout-text">Đăng xuất</span></button>
                </form>
            </div>
        </div>
    </header>

    <main class="content">
        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif

        <section class="intro">
            <div>
                <span class="eyebrow">{{ $dashboard['eyebrow'] }} · {{ $roleLabel }}</span>
                <h1>{{ $dashboard['title'] }}</h1>
                <p>{{ $dashboard['intro'] }}</p>
            </div>
            <span class="date-pill"><span class="material-symbols-outlined" style="font-size:16px">calendar_today</span>{{ now()->format('d/m/Y') }}</span>
        </section>

        <section class="stats" aria-label="Chỉ số tổng quan">
            @foreach ($dashboard['stats'] as $stat)
                <article class="stat">
                    <div class="stat-top"><span class="stat-label">{{ $stat['label'] }}</span><span class="stat-icon material-symbols-outlined">{{ $stat['icon'] }}</span></div>
                    <div class="stat-value">{{ $stat['value'] }}</div>
                    <div class="stat-hint">{{ $stat['hint'] }}</div>
                </article>
            @endforeach
        </section>

        @if ($dashboardType === 'coach')
            <section class="panel">
                <div class="panel-head">
                    <h2>Danh sách huấn luyện viên</h2>
                    <span class="status-chip"><span class="status-dot"></span>{{ $coachCount }} HLV</span>
                </div>
                @if ($coaches->isNotEmpty())
                    <div class="coach-grid">
                        @foreach ($coaches as $coach)
                            <article class="coach-card">
                                <div class="coach-photo">
                                    @if ($coach->avatar)
                                        <img src="{{ $coach->avatar }}" alt="{{ $coach->full_name }}">
                                    @else
                                        <span class="material-symbols-outlined">sports_tennis</span>
                                    @endif
                                    <span class="coach-rating"><span class="material-symbols-outlined">star</span>{{ number_format((float) $coach->rating_avg, 2) }}</span>
                                </div>
                                <div class="coach-info">
                                    <h3>{{ $coach->full_name }}</h3>
                                    <p class="coach-title">{{ $coach->title }}</p>
                                    <p class="coach-bio">{{ $coach->bio ?: 'Huấn luyện viên GoSport.' }}</p>
                                    <div class="coach-meta">
                                        <span>{{ number_format((int) $coach->students_count) }} học viên</span>
                                        <strong>{{ number_format((float) $coach->hourly_rate, 0, ',', '.') }}đ/giờ</strong>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <span class="empty-icon material-symbols-outlined">sports_tennis</span>
                        <strong>Chưa có huấn luyện viên</strong>
                        <p>Danh sách sẽ hiển thị khi có thông tin HLV trong hệ thống.</p>
                    </div>
                @endif
            </section>
        @else
        <section class="work-grid">
            <article class="panel">
                <div class="panel-head"><h2>{{ $dashboard['panel'] }}</h2><span class="status-chip"><span class="status-dot"></span> GoSport Workspace</span></div>
                <div class="empty-state">
                    <span class="empty-icon material-symbols-outlined">{{ $dashboard['icon'] }}</span>
                    <strong>{{ $dashboard['empty'] }}</strong>
                    <p>{{ $dashboard['emptyText'] }}</p>
                </div>
            </article>

            <aside class="panel">
                <div class="panel-head"><h2>Không gian làm việc</h2></div>
                <div class="side-list">
                    <div class="side-item"><span class="material-symbols-outlined">account_circle</span><div><strong>{{ auth()->user()->name }}</strong><span>{{ $roleDescription }}</span></div></div>
                    <div class="side-item"><span class="material-symbols-outlined">verified_user</span><div><strong>Tài khoản {{ $roleLabel }}</strong><span>Đăng nhập an toàn vào hệ thống GoSport.</span></div></div>
                    <div class="side-item"><span class="material-symbols-outlined">info</span><div><strong>Bảng điều khiển</strong><span>Giao diện sẵn sàng; dữ liệu sẽ xuất hiện khi các nghiệp vụ được kết nối.</span></div></div>
                </div>
            </aside>
        </section>
        @endif
    </main>
</div>
</body>
</html>
