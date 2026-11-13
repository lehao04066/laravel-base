<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $roleLabel }} | GoSport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { display: grid; min-height: 100vh; margin: 0; place-items: center; padding: 24px; background: #f3f6fb; color: #172033; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card { width: min(100%, 560px); padding: 40px; border: 1px solid #e7ebf2; border-radius: 26px; background: #fff; box-shadow: 0 20px 55px #14234512; }
        .brand { color: #111827; font: 700 24px 'Space Grotesk', sans-serif; letter-spacing: -1px; }
        .brand span { color: #2563eb; }
        .tag { display: inline-block; margin-top: 31px; padding: 8px 11px; border-radius: 99px; background: #eff6ff; color: #2563eb; font-size: 10px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase; }
        h1 { margin: 13px 0 9px; font: 700 31px 'Space Grotesk', sans-serif; letter-spacing: -.8px; }
        p { color: #718096; font-size: 13px; line-height: 1.75; }
        .identity { margin: 25px 0; padding: 17px; border: 1px solid #e8edf5; border-radius: 15px; background: #f8fafd; }
        .identity strong { display: block; color: #172033; font-size: 13px; }
        .identity span { display: inline-block; margin-top: 6px; color: #718096; font-size: 11px; }
        .logout { display: inline-flex; min-height: 45px; align-items: center; justify-content: center; padding: 0 17px; border: 0; border-radius: 12px; background: #2563eb; color: white; font: 700 12px 'Plus Jakarta Sans', sans-serif; cursor: pointer; }
        .success { margin: 18px 0 0; color: #047857; font-size: 11px; }
        @media (max-width: 520px) { .card { padding: 29px 24px; } h1 { font-size: 27px; } }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">Go<span>Sport</span></div>
        <span class="tag">{{ $roleLabel }}</span>
        <h1>Đăng nhập thành công</h1>
        <p>{{ $roleDescription }}</p>
        <div class="identity">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->email }} · {{ $roleLabel }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout" type="submit">Đăng xuất an toàn</button>
        </form>
        @if (session('success'))
            <p class="success" role="status">{{ session('success') }}</p>
        @endif
    </main>
</body>
</html>
