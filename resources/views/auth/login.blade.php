<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập | GoSport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light; --navy: #0b1735; --blue: #2563eb; --muted: #718096; --mint: #61e3c0; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: #f3f6fb; color: #172033; font-family: 'Plus Jakarta Sans', sans-serif; }
        button, input { font: inherit; }
        .page { width: min(1180px, calc(100% - 48px)); margin: 0 auto; padding: 25px 0 22px; }
        .brand { display: inline-flex; align-items: center; gap: 11px; color: #111827; text-decoration: none; }
        .brand-mark { display: grid; width: 43px; height: 43px; place-items: center; border-radius: 14px; background: #2563eb; color: white; box-shadow: 0 8px 20px #2563eb35; }
        .brand-mark svg { width: 25px; height: 25px; }
        .brand-name { font: 700 24px/1 'Space Grotesk', sans-serif; letter-spacing: -1px; }
        .brand-name span { color: var(--blue); }
        .brand-caption { margin-top: 4px; color: #8b96a9; font-size: 9px; font-weight: 800; letter-spacing: 1.7px; text-transform: uppercase; }
        .layout { display: grid; grid-template-columns: 1.08fr .92fr; gap: 25px; margin-top: 24px; align-items: stretch; }
        .hero { position: relative; display: flex; min-height: 650px; flex-direction: column; justify-content: space-between; overflow: hidden; padding: 45px 48px 30px; border-radius: 30px; color: #fff; background: linear-gradient(145deg, #10245a 0%, #111c3b 54%, #091326 100%); box-shadow: 0 22px 55px #12214b20; }
        .hero:before { position: absolute; inset: 0; background-image: radial-gradient(#d9f7ff44 .8px, transparent .8px); background-size: 17px 17px; content: ''; opacity: .23; mask-image: linear-gradient(130deg, #000 0%, transparent 66%); }
        .hero:after { position: absolute; top: -145px; right: -140px; width: 410px; height: 410px; border: 1px solid #ffffff12; border-radius: 50%; box-shadow: 0 0 0 50px #ffffff08, 0 0 0 100px #ffffff05; content: ''; }
        .hero-content, .hero-bottom { position: relative; z-index: 1; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; padding: 9px 13px; border: 1px solid #64e2c044; border-radius: 99px; background: #39cbaa17; color: #9af2dc; font-size: 11px; font-weight: 800; letter-spacing: .15px; }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; background: #61e3c0; box-shadow: 0 0 0 4px #61e3c022; }
        h1 { max-width: 560px; margin: 28px 0 16px; font: 700 clamp(38px, 5vw, 55px)/1.06 'Space Grotesk', sans-serif; letter-spacing: -2.4px; }
        h1 span { color: #74e8ce; }
        .hero-copy { max-width: 465px; margin: 0; color: #bac8e2; font-size: 14px; line-height: 1.85; }
        .hero-list { display: grid; gap: 13px; margin: 27px 0 0; padding: 0; list-style: none; color: #e2eafb; font-size: 12px; font-weight: 600; }
        .hero-list li { display: flex; align-items: center; gap: 11px; }
        .check { display: grid; width: 21px; height: 21px; flex: none; place-items: center; border-radius: 7px; background: #5de0bd1e; color: #70e7c8; font-size: 13px; }
        .quote { display: flex; align-items: center; gap: 14px; max-width: 455px; margin-top: 34px; padding: 15px; border: 1px solid #ffffff15; border-radius: 18px; background: #ffffff0d; backdrop-filter: blur(10px); }
        .quote-mark { display: grid; width: 39px; height: 39px; flex: none; place-items: center; border-radius: 12px; background: #f5b84020; color: #ffd574; font-size: 21px; }
        .quote p { margin: 0; color: #e1e8f5; font-size: 11px; line-height: 1.7; }
        .quote strong { display: block; margin-top: 5px; color: #86e8d0; font-size: 10px; }
        .hero-bottom { display: flex; gap: 0; margin-top: 34px; padding-top: 21px; border-top: 1px solid #ffffff1c; }
        .stat { flex: 1; padding-right: 12px; }
        .stat + .stat { padding-left: 17px; border-left: 1px solid #ffffff20; }
        .stat strong { display: block; color: #fff; font: 700 20px 'Space Grotesk', sans-serif; }
        .stat span { display: block; margin-top: 4px; color: #a8b6d0; font-size: 10px; }
        .panel-wrap { display: flex; align-items: center; }
        .panel { width: 100%; padding: 38px 38px 31px; border: 1px solid #e7ebf2; border-radius: 27px; background: #fff; box-shadow: 0 18px 52px #14234512; }
        .panel-tag { color: var(--blue); font-size: 10px; font-weight: 800; letter-spacing: 1.1px; text-transform: uppercase; }
        h2 { margin: 8px 0 8px; color: #131d32; font: 700 29px/1.2 'Space Grotesk', sans-serif; letter-spacing: -.8px; }
        .panel-copy { margin: 0 0 24px; color: var(--muted); font-size: 12px; line-height: 1.7; }
        .alert { margin: 0 0 17px; padding: 12px 14px; border-radius: 12px; font-size: 11px; line-height: 1.6; }
        .alert-error { border: 1px solid #fecdd3; background: #fff1f2; color: #be123c; }
        .alert-success { border: 1px solid #a7f3d0; background: #ecfdf5; color: #047857; }
        .field { margin-top: 17px; }
        .field-label { display: block; margin-bottom: 8px; color: #3b465a; font-size: 10px; font-weight: 800; letter-spacing: .65px; text-transform: uppercase; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; top: 50%; left: 14px; width: 18px; height: 18px; transform: translateY(-50%); color: #94a1b5; pointer-events: none; }
        .input { width: 100%; height: 49px; padding: 0 43px; border: 1px solid #e2e7ef; border-radius: 13px; outline: 0; background: #f8fafd; color: #18243b; font-size: 12px; transition: .18s ease; }
        .input::placeholder { color: #aab4c3; }
        .input:focus { border-color: #6d91f6; background: #fff; box-shadow: 0 0 0 4px #2563eb14; }
        .input.is-invalid { border-color: #fb7185; }
        .toggle { position: absolute; top: 50%; right: 13px; display: grid; width: 25px; height: 25px; transform: translateY(-50%); place-items: center; border: 0; background: transparent; color: #96a1b3; cursor: pointer; }
        .toggle svg { width: 17px; height: 17px; }
        .form-options { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin: 14px 0 20px; }
        .remember { display: inline-flex; align-items: center; gap: 8px; color: #596579; font-size: 11px; cursor: pointer; }
        .remember input { width: 15px; height: 15px; margin: 0; accent-color: var(--blue); }
        .secure-note { color: #9aa5b5; font-size: 10px; }
        .submit { display: flex; width: 100%; height: 51px; align-items: center; justify-content: center; gap: 10px; border: 0; border-radius: 13px; background: #2563eb; box-shadow: 0 11px 22px #2563eb35; color: #fff; font-size: 12px; font-weight: 800; cursor: pointer; transition: .2s ease; }
        .submit:hover { transform: translateY(-1px); background: #1d4ed8; box-shadow: 0 13px 25px #2563eb42; }
        .submit svg { width: 17px; height: 17px; }
        .divider { display: flex; align-items: center; gap: 11px; margin: 22px 0 16px; color: #a1aabb; font-size: 9px; font-weight: 800; letter-spacing: .7px; }
        .divider:before, .divider:after { height: 1px; flex: 1; background: #edf0f4; content: ''; }
        .roles-title { margin: 0 0 10px; color: #647084; font-size: 10px; font-weight: 700; }
        .roles { display: flex; flex-wrap: wrap; gap: 7px; }
        .role { padding: 7px 9px; border: 1px solid #edf0f5; border-radius: 9px; background: #fafbfc; color: #536074; font-size: 9px; font-weight: 700; }
        .panel-footer { margin: 23px 0 0; padding-top: 17px; border-top: 1px solid #edf0f4; color: #8a95a7; font-size: 10px; text-align: center; }
        .page-footer { padding-top: 18px; color: #9ba6b7; font-size: 10px; text-align: center; }
        @media (max-width: 900px) { .layout { grid-template-columns: 1fr; max-width: 610px; margin-right: auto; margin-left: auto; } .hero { min-height: 490px; padding: 35px; } .panel { padding: 34px; } }
        @media (max-width: 520px) { .page { width: min(100% - 28px, 440px); padding-top: 16px; } .brand-mark { width: 39px; height: 39px; } .brand-name { font-size: 22px; } .layout { gap: 14px; margin-top: 18px; } .hero { min-height: 430px; padding: 28px 23px 22px; border-radius: 23px; } h1 { margin-top: 24px; font-size: 39px; } .hero-copy { font-size: 12px; } .hero-list { gap: 10px; font-size: 11px; } .quote { margin-top: 24px; } .hero-bottom { margin-top: 25px; } .stat + .stat { padding-left: 10px; } .stat strong { font-size: 17px; } .stat span { font-size: 9px; } .panel { padding: 27px 22px 24px; border-radius: 23px; } h2 { font-size: 26px; } .secure-note { display: none; } }
    </style>
</head>
<body>
<div class="page">
    <a class="brand" href="{{ route('home') }}" aria-label="GoSport">
        <span class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7.2 3.7a6.8 6.8 0 0 1 9.6 9.6l-5.1 5.1a2.5 2.5 0 0 1-3.5-3.5l5.2-5.2"/><path d="m7.7 7.7 8.6 8.6M5 5l2 2m10 10 2 2"/></svg>
        </span>
        <span><span class="brand-name">Go<span>Sport</span></span><span class="brand-caption">Arena booking</span></span>
    </a>

    <main class="layout">
        <section class="hero" aria-label="Giới thiệu GoSport">
            <div class="hero-content">
                <div class="eyebrow"><span class="status-dot"></span> NỀN TẢNG THỂ THAO GO SPORT</div>
                <h1>Cháy hết mình<br><span>trên từng cú smash.</span></h1>
                <p class="hero-copy">Một tài khoản để kết nối với sân đấu, đội ngũ và cộng đồng GoSport. Đăng nhập để tiếp tục hành trình của bạn.</p>
                <ul class="hero-list">
                    <li><span class="check">✓</span> Đặt sân nhanh, chủ động lịch thi đấu</li>
                    <li><span class="check">✓</span> Kết nối thành viên và huấn luyện viên</li>
                    <li><span class="check">✓</span> Phân quyền phù hợp với từng vai trò</li>
                </ul>
                <div class="quote">
                    <span class="quote-mark" aria-hidden="true">✦</span>
                    <p>“Sân tốt, lịch rõ ràng, cả đội chỉ cần tập trung vào trận đấu.”<strong>Cộng đồng GoSport</strong></p>
                </div>
            </div>
            <div class="hero-bottom" aria-label="Thông tin hệ thống">
                <div class="stat"><strong>5</strong><span>Nhóm vai trò</span></div>
                <div class="stat"><strong>24/7</strong><span>Truy cập tài khoản</span></div>
                <div class="stat"><strong>Secure</strong><span>Bảo mật phiên đăng nhập</span></div>
            </div>
        </section>

        <section class="panel-wrap" aria-labelledby="login-title">
            <div class="panel">
                <div class="panel-tag">Thành viên GoSport</div>
                <h2 id="login-title">Chào mừng trở lại!</h2>
                <p class="panel-copy">Đăng nhập để truy cập khu vực dành riêng cho tài khoản của bạn.</p>

                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" autocomplete="on">
                    @csrf
                    <div class="field">
                        <label class="field-label" for="email">Email hoặc số điện thoại</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
                            <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="text" value="{{ old('email') }}" placeholder="name@example.com hoặc 098 888 9999" autocomplete="username" autocapitalize="off" required autofocus>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label" for="password">Mật khẩu</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3m-4 5v2"/></svg>
                            <input class="input" id="password" name="password" type="password" placeholder="Nhập mật khẩu của bạn" autocomplete="current-password" required>
                            <button class="toggle" id="password-toggle" type="button" aria-label="Hiện mật khẩu" aria-pressed="false">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.3-6 9.5-6 9.5 6 9.5 6-3.3 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Ghi nhớ đăng nhập</label>
                        <span class="secure-note">Thông tin được bảo mật</span>
                    </div>

                    <button class="submit" type="submit">
                        Đăng nhập
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </button>
                </form>

                <div class="divider">PHÂN QUYỀN TÀI KHOẢN</div>
                <p class="roles-title">Khu vực truy cập theo vai trò</p>
                <div class="roles" aria-label="Các vai trò">
                    <span class="role">Quản trị</span>
                    <span class="role">Chủ sân</span>
                    <span class="role">Nhân viên</span>
                    <span class="role">Huấn luyện viên</span>
                    <span class="role">Thành viên</span>
                </div>
                <p class="panel-footer">Cần hỗ trợ đăng nhập? Vui lòng liên hệ quản trị viên GoSport.</p>
            </div>
        </section>
    </main>

    <footer class="page-footer">© {{ date('Y') }} GoSport Arena · Đặt sân, kết nối, bứt phá.</footer>
</div>
<script>
    document.getElementById('password-toggle').addEventListener('click', function () {
        const password = document.getElementById('password');
        const visible = password.type === 'password';
        password.type = visible ? 'text' : 'password';
        this.setAttribute('aria-pressed', visible ? 'true' : 'false');
        this.setAttribute('aria-label', visible ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
    });
</script>
</body>
</html>
