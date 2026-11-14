<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Điều phối sân | GoSport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light; --ink: #17241f; --muted: #78857e; --line: #e9eee9; --green: #16835d; --canvas: #f6f8f5; }
        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; background: var(--canvas); color: var(--ink); font-family: 'DM Sans', sans-serif; font-size: 13px; }
        button, input { font: inherit; }
        button { color: inherit; }
        .app { display: grid; min-height: 100vh; grid-template-columns: 238px minmax(0, 1fr); }
        .sidebar { position: sticky; top: 0; display: flex; height: 100vh; flex-direction: column; padding: 25px 17px 18px; border-right: 1px solid var(--line); background: #fff; }
        .brand { display: flex; align-items: center; gap: 10px; padding: 0 9px; color: var(--ink); text-decoration: none; }
        .brand-mark { display: grid; width: 37px; height: 37px; place-items: center; border-radius: 12px; background: #e8f5ee; color: var(--green); }
        .brand-mark svg { width: 21px; height: 21px; }
        .brand-name { font: 800 19px/1 'Manrope', sans-serif; letter-spacing: -1px; }
        .brand-name span { color: var(--green); }
        .brand-sub { display: block; margin-top: 4px; color: #97a39c; font-size: 9px; font-weight: 700; letter-spacing: 1.15px; text-transform: uppercase; }
        .workspace { display: flex; align-items: center; gap: 10px; margin: 31px 1px 25px; padding: 12px 10px; border: 1px solid var(--line); border-radius: 12px; }
        .venue-icon { display: grid; width: 34px; height: 34px; flex: none; place-items: center; border-radius: 10px; background: #f1f5f1; color: #5a7062; }
        .venue-icon svg { width: 17px; height: 17px; }
        .venue-meta { min-width: 0; flex: 1; }
        .venue-name { overflow: hidden; color: #334139; font-size: 11px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .venue-location { margin-top: 4px; color: #96a198; font-size: 10px; }
        .nav-label { margin: 0 10px 9px; color: #a0aaa3; font-size: 9px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .nav { display: grid; gap: 4px; }
        .nav-link { display: flex; min-height: 41px; align-items: center; gap: 11px; padding: 0 11px; border-radius: 10px; color: #78847d; font-size: 11px; font-weight: 600; text-decoration: none; }
        .nav-link svg { width: 17px; height: 17px; flex: none; }
        .nav-link.active { background: #eaf5ee; color: #147d58; font-weight: 700; }
        .nav-count { margin-left: auto; padding: 3px 7px; border-radius: 99px; background: #fff; color: #527d65; font-size: 9px; font-weight: 800; }
        .side-spacer { flex: 1; }
        .help-card { padding: 13px; border: 1px solid #e8eee8; border-radius: 13px; background: #f8faf7; }
        .help-title { color: #35433a; font-size: 10px; font-weight: 700; }
        .help-copy { margin: 5px 0 11px; color: #89958d; font-size: 10px; line-height: 1.6; }
        .help-link { color: var(--green); font-size: 10px; font-weight: 700; text-decoration: none; }
        .account { display: flex; align-items: center; gap: 10px; margin-top: 15px; padding: 13px 3px 0; border-top: 1px solid var(--line); }
        .avatar { display: grid; width: 33px; height: 33px; flex: none; place-items: center; border-radius: 50%; background: #e5eee7; color: #52705c; font-size: 10px; font-weight: 800; }
        .account-info { min-width: 0; flex: 1; }
        .account-name { overflow: hidden; color: #344139; font-size: 10px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .account-role { margin-top: 3px; color: #9aa49c; font-size: 9px; }
        .logout { display: grid; width: 29px; height: 29px; place-items: center; border: 0; border-radius: 8px; background: transparent; color: #909b93; cursor: pointer; }
        .logout:hover { background: #f1f4f0; color: #b04747; }
        .logout svg { width: 16px; height: 16px; }
        .main { min-width: 0; padding: 0 34px 35px; }
        .topbar { display: flex; min-height: 72px; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 1px solid var(--line); }
        .crumb { color: #99a39c; font-size: 10px; }
        .crumb strong { color: #455149; font-weight: 600; }
        .top-actions { display: flex; align-items: center; gap: 10px; }
        .live-pill { display: inline-flex; align-items: center; gap: 7px; color: #5f7568; font-size: 10px; font-weight: 600; }
        .live-dot { width: 7px; height: 7px; border-radius: 50%; background: #31ae73; box-shadow: 0 0 0 3px #31ae731a; }
        .icon-button { display: grid; width: 35px; height: 35px; place-items: center; border: 1px solid var(--line); border-radius: 10px; background: #fff; color: #718078; cursor: pointer; }
        .icon-button svg { width: 16px; height: 16px; }
        .content { width: min(100%, 1260px); margin: 0 auto; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin: 29px 0 22px; }
        .eyebrow { color: #718378; font-size: 9px; font-weight: 800; letter-spacing: 1.1px; text-transform: uppercase; }
        h1 { margin: 7px 0 5px; font: 800 25px/1.2 'Manrope', sans-serif; letter-spacing: -.8px; }
        .subheading { margin: 0; color: #839087; font-size: 11px; }
        .date-controls { display: flex; align-items: center; gap: 7px; }
        .date-nav, .today-button { display: inline-flex; height: 36px; align-items: center; justify-content: center; gap: 7px; padding: 0 10px; border: 1px solid #e3e9e3; border-radius: 9px; background: #fff; color: #57655c; font-size: 10px; font-weight: 700; cursor: pointer; }
        .date-nav { width: 34px; padding: 0; }
        .date-nav svg { width: 14px; height: 14px; }
        .today-button { padding: 0 13px; }
        .date-label { min-width: 157px; padding: 0; border: 0; background: transparent; color: #39463e; font-size: 11px; font-weight: 700; text-align: center; cursor: pointer; }
        .date-input { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; }
        .stats { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 12px; margin-bottom: 20px; }
        .stat-card { display: flex; min-height: 94px; align-items: center; gap: 13px; padding: 16px; border: 1px solid #e8ede8; border-radius: 13px; background: #fff; }
        .stat-icon { display: grid; width: 39px; height: 39px; flex: none; place-items: center; border-radius: 12px; }
        .stat-icon svg { width: 19px; height: 19px; }
        .stat-icon.total { background: #eff2f0; color: #57685d; }
        .stat-icon.playing { background: #e6f5eb; color: #198654; }
        .stat-icon.upcoming { background: #fff3df; color: #be8429; }
        .stat-icon.available { background: #edf2ff; color: #687bd0; }
        .stat-icon.maintenance { background: #fff0ef; color: #be6256; }
        .stat-value { font: 800 21px/1 'Manrope', sans-serif; letter-spacing: -.5px; }
        .stat-label { margin-top: 5px; color: #839087; font-size: 10px; }
        .stat-foot { margin-top: 4px; color: #a0aaa3; font-size: 9px; }
        .section-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin: 0 0 12px; }
        .section-title { margin: 0; font: 800 15px 'Manrope', sans-serif; letter-spacing: -.3px; }
        .section-caption { margin: 4px 0 0; color: #929d95; font-size: 10px; }
        .toolbar { display: flex; align-items: center; gap: 9px; }
        .search { position: relative; }
        .search svg { position: absolute; top: 50%; left: 10px; width: 14px; height: 14px; transform: translateY(-50%); color: #9aa59d; pointer-events: none; }
        .search input { width: 195px; height: 34px; padding: 0 10px 0 31px; border: 1px solid #e5eae5; border-radius: 9px; outline: 0; background: #fff; color: #344139; font-size: 10px; }
        .search input:focus { border-color: #9dc8ad; box-shadow: 0 0 0 3px #16835d12; }
        .filter { height: 34px; padding: 0 10px; border: 1px solid #e5eae5; border-radius: 9px; background: #fff; color: #58665d; font-size: 10px; font-weight: 600; }
        .filters { display: flex; gap: 6px; margin: 0 0 13px; }
        .filter-chip { display: inline-flex; min-height: 29px; align-items: center; gap: 7px; padding: 0 10px; border: 1px solid transparent; border-radius: 8px; background: transparent; color: #849087; font-size: 10px; font-weight: 600; cursor: pointer; }
        .filter-chip.active { border-color: #e5ece5; background: #fff; color: #344139; box-shadow: 0 1px 2px #17241f08; }
        .chip-count { color: #a1aaa4; font-size: 9px; }
        .court-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .court-card { min-width: 0; padding: 15px 16px 13px; border: 1px solid #e7ece7; border-radius: 13px; background: #fff; transition: border-color .16s ease, box-shadow .16s ease; }
        .court-card:hover { border-color: #cddbd0; box-shadow: 0 6px 18px #17241f08; }
        .court-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
        .court-id { display: flex; align-items: center; gap: 10px; }
        .court-icon { display: grid; width: 37px; height: 37px; flex: none; place-items: center; border-radius: 11px; background: #f0f5f1; color: #5e7967; }
        .court-icon svg { width: 19px; height: 19px; }
        .court-name { color: #2f3e35; font: 700 12px 'Manrope', sans-serif; }
        .court-type { margin-top: 4px; color: #9aa49d; font-size: 9px; }
        .status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 8px; border-radius: 99px; font-size: 9px; font-weight: 700; white-space: nowrap; }
        .status:before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ''; }
        .status.playing { background: #e8f6ed; color: #198451; }
        .status.upcoming { background: #fff4e2; color: #b98026; }
        .status.available { background: #eef2ff; color: #6677c7; }
        .status.maintenance { background: #fff0ef; color: #be6256; }
        .status.completed { background: #eff2f0; color: #66736b; }
        .booking { margin: 14px 0 12px; padding: 11px 12px; border-radius: 10px; background: #f8faf8; }
        .booking-label { display: flex; align-items: center; justify-content: space-between; gap: 10px; color: #9aa49d; font-size: 9px; }
        .booking-time { color: #506056; font-size: 9px; font-weight: 700; }
        .guest-row { display: flex; align-items: center; gap: 8px; margin-top: 9px; }
        .guest-avatar { display: grid; width: 27px; height: 27px; flex: none; place-items: center; border-radius: 50%; background: #e8eee8; color: #617767; font-size: 8px; font-weight: 800; }
        .guest-name { overflow: hidden; color: #3d4b42; font-size: 10px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .guest-note { margin-top: 3px; color: #9aa49d; font-size: 9px; }
        .booking-code { margin-left: auto; color: #a3aca6; font-size: 9px; white-space: nowrap; }
        .progress-track { height: 4px; overflow: hidden; margin-top: 10px; border-radius: 99px; background: #e8ede8; }
        .progress-value { height: 100%; border-radius: inherit; background: #43b57d; }
        .court-footer { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding-top: 10px; border-top: 1px solid #f0f2f0; color: #929d95; font-size: 9px; }
        .court-footer strong { color: #58675d; font-weight: 700; }
        .empty-booking { display: flex; min-height: 72px; align-items: center; gap: 10px; margin: 14px 0 12px; padding: 11px 12px; border-radius: 10px; background: #f8faf8; color: #909b93; font-size: 10px; }
        .empty-booking svg { width: 17px; height: 17px; color: #83a28c; }
        .empty-state { display: none; padding: 35px 20px; border: 1px dashed #dfe7df; border-radius: 13px; background: #fff; color: #839087; font-size: 11px; text-align: center; }
        .empty-state.visible { display: block; }
        .footer-note { margin-top: 14px; color: #a0aaa3; font-size: 9px; text-align: right; }
        @media (max-width: 1050px) { .main { padding-right: 23px; padding-left: 23px; } .stats { gap: 9px; } .stat-card { padding: 13px 11px; } .court-grid { gap: 9px; } }
        @media (max-width: 760px) { .app { grid-template-columns: 68px minmax(0, 1fr); } .sidebar { align-items: center; padding: 20px 9px 14px; } .brand { padding: 0; } .brand > span:last-child, .workspace, .nav-label, .nav-link span, .nav-count, .help-card, .account-info, .logout { display: none; } .nav { width: 100%; margin-top: 34px; } .nav-link { justify-content: center; padding: 0; } .nav-link svg { width: 18px; height: 18px; } .account { justify-content: center; padding-top: 12px; } .main { padding-right: 18px; padding-left: 18px; } .stats { grid-template-columns: repeat(2, minmax(0,1fr)); } .court-grid { grid-template-columns: 1fr; } }
        @media (max-width: 520px) { .main { padding: 0 13px 25px; } .topbar { min-height: 59px; } .crumb { font-size: 9px; } .live-pill { font-size: 0; gap: 0; } .top-actions { gap: 7px; } .page-heading { align-items: flex-start; flex-direction: column; gap: 13px; margin: 21px 0 17px; } h1 { font-size: 22px; } .date-controls { width: 100%; justify-content: space-between; } .date-label { min-width: auto; flex: 1; font-size: 10px; } .today-button { padding: 0 9px; } .stats { gap: 8px; margin-bottom: 19px; } .stat-card { min-height: 80px; gap: 9px; padding: 10px; } .stat-icon { width: 33px; height: 33px; border-radius: 10px; } .stat-icon svg { width: 16px; height: 16px; } .stat-value { font-size: 18px; } .stat-label { font-size: 9px; } .stat-foot { font-size: 8px; } .section-head { align-items: flex-start; flex-direction: column; } .toolbar { width: 100%; } .search { flex: 1; } .search input { width: 100%; } .filter { max-width: 104px; } .filters { overflow-x: auto; } .filter-chip { flex: none; } }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar" aria-label="Điều hướng chính">
        <a class="brand" href="{{ route('dashboard.staff') }}" aria-label="GoSport">
            <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7.2 3.7a6.8 6.8 0 0 1 9.6 9.6l-5.1 5.1a2.5 2.5 0 0 1-3.5-3.5l5.2-5.2"/><path d="m7.7 7.7 8.6 8.6"/></svg></span>
            <span><span class="brand-name">Go<span>Sport</span></span><span class="brand-sub">Arena operations</span></span>
        </a>

        <div class="workspace">
            <span class="venue-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01M10 21v-4h4v4"/></svg></span>
            <div class="venue-meta"><div class="venue-name">{{ $venue->name }}</div><div class="venue-location">{{ $venue->district }}, {{ $venue->city }}</div></div>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9aa49d" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </div>

        <p class="nav-label">Vận hành</p>
        <nav class="nav">
            <a class="nav-link active" href="#courts" aria-current="page"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M12 4v16M3 12h18"/></svg><span>Điều phối sân</span></a>
            <a class="nav-link" href="#courts"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg><span>Lịch đặt sân</span><span class="nav-count">12</span></a>
            <a class="nav-link" href="#courts"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M3 20v-1a6 6 0 0 1 12 0v1M16 5.2a3.5 3.5 0 0 1 0 6.6M18 14a5 5 0 0 1 3 4.6v1"/></svg><span>Khách hàng</span></a>
            <a class="nav-link" href="#courts"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19V5m0 14h17M8 15l4-4 3 2 5-6"/></svg><span>Báo cáo ca</span></a>
        </nav>

        <div class="side-spacer"></div>
        <div class="help-card"><div class="help-title">Cần hỗ trợ?</div><p class="help-copy">Liên hệ quản lý nếu bạn cần trợ giúp trong ca làm.</p><a class="help-link" href="mailto:support@gosport.vn">Liên hệ hỗ trợ →</a></div>
        <div class="account">
            <span class="avatar" aria-hidden="true">{{ collect(explode(' ', auth()->user()->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}</span>
            <div class="account-info"><div class="account-name">{{ auth()->user()->name }}</div><div class="account-role">Lễ tân</div></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit" aria-label="Đăng xuất"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg></button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="crumb">Vận hành <span aria-hidden="true">/</span> <strong>Điều phối sân</strong></div>
            <div class="top-actions">
                <span class="live-pill"><span class="live-dot"></span> Cập nhật mới nhất</span>
                <button class="icon-button" type="button" aria-label="Thông báo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 13h4"/></svg></button>
            </div>
        </header>

        <div class="content">
            <section class="page-heading" aria-labelledby="page-title">
                <div>
                    <div class="eyebrow">Bảng điều khiển lễ tân</div>
                    <h1 id="page-title">Tình hình sân trong ngày</h1>
                    <p class="subheading">Theo dõi lượt chơi và tình trạng sân trong ca trực.</p>
                </div>
                <div class="date-controls" aria-label="Chọn ngày">
                    <button class="date-nav" id="previous-day" type="button" aria-label="Ngày trước"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg></button>
                    <button class="date-label" id="date-label" type="button" aria-label="Chọn ngày"></button>
                    <input class="date-input" id="date-picker" type="date" value="{{ $selectedDate->format('Y-m-d') }}" aria-label="Chọn ngày">
                    <button class="date-nav" id="next-day" type="button" aria-label="Ngày tiếp theo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></button>
                    <button class="today-button" id="today-button" type="button">Hôm nay</button>
                </div>
            </section>

            <section class="stats" aria-label="Tổng quan sân">
                <article class="stat-card"><span class="stat-icon total"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M12 4v16M3 12h18"/></svg></span><div><div class="stat-value" id="total-count">{{ $counts['all'] }}</div><div class="stat-label">Tổng số sân</div><div class="stat-foot">{{ $venue->name }}</div></div></article>
                <article class="stat-card"><span class="stat-icon playing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg></span><div><div class="stat-value" id="playing-count">{{ $counts['playing'] }}</div><div class="stat-label">Đang có khách chơi</div><div class="stat-foot">Đã check-in, chưa check-out</div></div></article>
                <article class="stat-card"><span class="stat-icon upcoming"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><div class="stat-value" id="upcoming-count">{{ $counts['upcoming'] }}</div><div class="stat-label">Sắp có khách</div><div class="stat-foot">Có lịch đặt trong ngày</div></div></article>
                <article class="stat-card"><span class="stat-icon available"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg></span><div><div class="stat-value" id="available-count">{{ $counts['available'] }}</div><div class="stat-label">Sân đang trống</div><div class="stat-foot">Có thể nhận khách</div></div></article>
                <article class="stat-card"><span class="stat-icon maintenance"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4l-3 3-3-3z"/></svg></span><div><div class="stat-value" id="maintenance-count">{{ $counts['maintenance'] }}</div><div class="stat-label">Bảo trì / ngưng</div><div class="stat-foot">Không nhận khách</div></div></article>
            </section>

            <section id="courts" aria-labelledby="courts-title">
                <div class="section-head">
                    <div><h2 class="section-title" id="courts-title">Danh sách sân</h2><p class="section-caption">Trạng thái sử dụng và thông tin khách theo từng sân.</p></div>
                    <div class="toolbar">
                        <label class="search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input id="court-search" type="search" placeholder="Tìm sân hoặc tên khách..." aria-label="Tìm sân hoặc tên khách"></label>
                        <select class="filter" id="court-type" aria-label="Lọc loại sân"><option value="all">Tất cả loại sân</option>@foreach ($courtTypes as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach</select>
                    </div>
                </div>
                <div class="filters" role="group" aria-label="Lọc theo trạng thái">
                    <button class="filter-chip active" type="button" data-filter="all" aria-pressed="true">Tất cả sân <span class="chip-count" data-count="all">{{ $counts['all'] }}</span></button>
                    <button class="filter-chip" type="button" data-filter="playing" aria-pressed="false">Đang chơi <span class="chip-count" data-count="playing">{{ $counts['playing'] }}</span></button>
                    <button class="filter-chip" type="button" data-filter="upcoming" aria-pressed="false">Sắp đến <span class="chip-count" data-count="upcoming">{{ $counts['upcoming'] }}</span></button>
                    <button class="filter-chip" type="button" data-filter="available" aria-pressed="false">Đang trống <span class="chip-count" data-count="available">{{ $counts['available'] }}</span></button>
                    <button class="filter-chip" type="button" data-filter="maintenance" aria-pressed="false">Bảo trì <span class="chip-count" data-count="maintenance">{{ $counts['maintenance'] }}</span></button>
                    <button class="filter-chip" type="button" data-filter="completed" aria-pressed="false">Đã kết thúc <span class="chip-count" data-count="completed">{{ $counts['completed'] }}</span></button>
                </div>

                <div class="court-grid" id="court-grid">
                    @forelse ($courts as $court)
                        <article class="court-card" data-status="{{ $court['status'] }}" data-type="{{ $court['type'] }}" data-search="{{ $court['search'] }}">
                            <div class="court-top">
                                <div class="court-id">
                                    <span class="court-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M12 4v16M3 12h18"/></svg></span>
                                    <div><div class="court-name">{{ $court['name'] }}</div><div class="court-type">{{ $court['type'] }} · {{ $court['surface'] }}</div></div>
                                </div>
                                <span class="status {{ $court['status'] }}">{{ $court['status_label'] }}</span>
                            </div>
                            @if ($court['booking'])
                                <div class="booking">
                                    <div class="booking-label"><span>{{ $court['booking_label'] }}</span><span class="booking-time">{{ $court['time_range'] }}</span></div>
                                    <div class="guest-row">
                                        <span class="guest-avatar">{{ $court['guest_initials'] }}</span>
                                        <div><div class="guest-name">{{ $court['guest_name'] }}</div><div class="guest-note">{{ $court['payment_label'] }}</div></div>
                                        <span class="booking-code">{{ $court['booking_code'] }}</span>
                                    </div>
                                    @if ($court['status'] === 'playing')
                                        <div class="progress-track"><div class="progress-value" style="width: {{ $court['progress'] }}%"></div></div>
                                    @endif
                                </div>
                            @elseif ($court['status'] === 'maintenance')
                                <div class="empty-booking"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4l-3 3-3-3z"/></svg>{{ $court['maintenance_reason'] ?: $court['footer'] }}</div>
                            @else
                                <div class="empty-booking"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Chưa có lượt đặt tiếp theo</div>
                            @endif
                            <div class="court-footer">
                                <span>@if ($court['status'] === 'playing') Còn lại <strong>{{ $court['remaining_minutes'] }} phút</strong> @else {{ $court['footer'] }} @endif</span>
                                @if ($court['booking'])<span>Mã {{ $court['booking_code'] }}</span>@endif
                            </div>
                        </article>
                    @empty
                        <div class="empty-state visible">Cơ sở này chưa có sân hoạt động.</div>
                    @endforelse
                </div>
                <div class="empty-state" id="empty-state">Không tìm thấy sân phù hợp. Hãy thử đổi từ khóa hoặc bộ lọc.</div>
                <p class="footer-note">Dữ liệu cập nhật lúc {{ $updatedAt->format('H:i') }}</p>
            </section>
        </div>
    </main>
</div>
<script>
    (() => {
        const cards = Array.from(document.querySelectorAll('.court-card'));
        const search = document.getElementById('court-search');
        const typeFilter = document.getElementById('court-type');
        const emptyState = document.getElementById('empty-state');
        const picker = document.getElementById('date-picker');
        const dateLabel = document.getElementById('date-label');
        let statusFilter = 'all';
        const today = new Date();

        const localDateValue = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        const showDate = (value) => {
            const date = new Date(`${value}T12:00:00`);
            dateLabel.textContent = new Intl.DateTimeFormat('vi-VN', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(date);
            picker.value = value;
        };
        const updateCounts = () => {
            const counts = { all: cards.length, playing: 0, upcoming: 0, available: 0, maintenance: 0, completed: 0 };
            cards.forEach((card) => { counts[card.dataset.status]++; });
            Object.keys(counts).forEach((key) => {
                document.querySelector(`[data-count="${key}"]`).textContent = counts[key];
            });
            document.getElementById('total-count').textContent = counts.all;
            document.getElementById('playing-count').textContent = counts.playing;
            document.getElementById('upcoming-count').textContent = counts.upcoming;
            document.getElementById('available-count').textContent = counts.available;
            document.getElementById('maintenance-count').textContent = counts.maintenance;
        };
        const filterCards = () => {
            const query = search.value.trim().toLocaleLowerCase('vi');
            let visible = 0;
            cards.forEach((card) => {
                const matchesStatus = statusFilter === 'all' || card.dataset.status === statusFilter;
                const matchesType = typeFilter.value === 'all' || card.dataset.type === typeFilter.value;
                const matchesSearch = card.dataset.search.toLocaleLowerCase('vi').includes(query);
                const show = matchesStatus && matchesType && matchesSearch;
                card.hidden = !show;
                if (show) visible++;
            });
            emptyState.classList.toggle('visible', visible === 0);
        };
        const navigateToDate = (value) => {
            const url = new URL(window.location.href);
            url.searchParams.set('date', value);
            window.location.assign(url);
        };

        document.querySelectorAll('.filter-chip').forEach((button) => {
            button.addEventListener('click', () => {
                statusFilter = button.dataset.filter;
                document.querySelectorAll('.filter-chip').forEach((chip) => {
                    const active = chip === button;
                    chip.classList.toggle('active', active);
                    chip.setAttribute('aria-pressed', String(active));
                });
                filterCards();
            });
        });
        search.addEventListener('input', filterCards);
        typeFilter.addEventListener('change', filterCards);
        dateLabel.addEventListener('click', () => picker.showPicker ? picker.showPicker() : picker.click());
        picker.addEventListener('change', () => { if (picker.value) navigateToDate(picker.value); });
        document.getElementById('today-button').addEventListener('click', () => navigateToDate(localDateValue(today)));
        document.getElementById('previous-day').addEventListener('click', () => {
            const date = new Date(`${picker.value}T12:00:00`);
            date.setDate(date.getDate() - 1);
            navigateToDate(localDateValue(date));
        });
        document.getElementById('next-day').addEventListener('click', () => {
            const date = new Date(`${picker.value}T12:00:00`);
            date.setDate(date.getDate() + 1);
            navigateToDate(localDateValue(date));
        });

        showDate(picker.value);
        updateCounts();
    })();
</script>
</body>
</html>
