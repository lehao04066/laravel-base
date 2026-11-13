<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập GoSport</title>
</head>
<body style="font-family:system-ui,sans-serif;max-width:420px;margin:4rem auto;padding:1rem">
    <h1>Đăng nhập</h1>
    @if ($errors->any())
        <p role="alert" style="color:#b91c1c">{{ $errors->first() }}</p>
    @endif
    <form method="post" action="{{ route('login.store') }}">
        @csrf
        <label>Email<br><input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" style="width:100%;padding:.6rem"></label><br><br>
        <label>Mật khẩu<br><input type="password" name="password" required autocomplete="current-password" style="width:100%;padding:.6rem"></label><br><br>
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>
