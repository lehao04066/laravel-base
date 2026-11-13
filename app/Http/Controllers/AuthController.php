<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const SUPPORTED_ROLES = [
        'super_admin', 'admin', 'owner', 'staff', 'coach', 'customer',
    ];

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->landingRoute(Auth::user()));
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $rawIdentifier = trim($credentials['email']);
        if (filter_var($rawIdentifier, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
            $identifier = Str::lower($rawIdentifier);
        } else {
            $field = 'phone';
            $identifier = preg_replace('/\D+/', '', $rawIdentifier) ?? '';

            if (str_starts_with($identifier, '84')) {
                $identifier = '0'.substr($identifier, 2);
            }
        }

        $throttleKey = Str::transliterate(Str::lower($identifier).'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.",
            ])->onlyInput('email');
        }

        $attempt = [$field => $identifier, 'password' => $credentials['password']];
        if (! Auth::attempt($attempt, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'email' => 'Email/số điện thoại hoặc mật khẩu không chính xác.',
            ])->onlyInput('email');
        }

        $user = Auth::user();
        if (! $user || ! $user->hasAnyRole(self::SUPPORTED_ROLES)) {
            Auth::logout();
            RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'email' => 'Tài khoản chưa được gán vai trò truy cập. Vui lòng liên hệ quản trị viên.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        RateLimiter::clear($throttleKey);

        return redirect()->intended(route($this->landingRoute($user)))
            ->with('success', 'Đăng nhập thành công. Chào mừng bạn đến với GoSport!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất an toàn.');
    }

    private function landingRoute(User $user): string
    {
        return match (true) {
            $user->hasAnyRole(['super_admin', 'admin']) => 'dashboard.admin',
            $user->hasRole('owner') => 'dashboard.owner',
            $user->hasRole('staff') => 'dashboard.staff',
            $user->hasRole('coach') => 'dashboard.coach',
            default => 'dashboard.customer',
        };
    }
}
