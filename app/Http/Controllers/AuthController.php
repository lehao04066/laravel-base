<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WalletAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        return redirect()->route($this->landingRoute($user))
            ->with('success', 'Đăng nhập thành công. Chào mừng bạn đến với GoSport!');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->landingRoute(Auth::user()));
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => Str::lower(trim((string) $request->input('email'))),
            'phone' => $this->normalizeVietnamesePhone((string) $request->input('phone')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^0\d{9,10}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'skill_level' => [
                'nullable',
                'string',
                'in:beginner,intermediate_minus,intermediate,intermediate_plus,advanced',
            ],
            'play_style' => [
                'nullable',
                'string',
                'in:Tấn công Smash,Công thủ toàn diện,Điều cầu phản tạt,Phòng thủ phản công',
            ],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký tài khoản.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải có 10 hoặc 11 chữ số theo định dạng Việt Nam (ví dụ: 0912345678 hoặc +84912345678).',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không trùng khớp.',
            'skill_level.in' => 'Vui lòng chọn trình độ thi đấu hợp lệ.',
            'play_style.in' => 'Vui lòng chọn phong cách chơi hợp lệ.',
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => $validated['password'],
                'role' => 'customer',
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'skill_level' => $validated['skill_level'] ?? 'intermediate',
                'play_style' => $validated['play_style'] ?? 'Tấn công Smash',
            ]);

            WalletAccount::create([
                'user_id' => $user->id,
                'balance' => 0,
                'frozen_balance' => 0,
                'currency' => 'VND',
                'status' => 'active',
            ]);

            $customerRole = Role::firstOrCreate(
                ['name' => 'customer'],
                [
                    'display_name' => 'Thành viên',
                    'description' => 'Đặt sân và kết nối với cộng đồng GoSport.',
                ],
            );
            $user->roles()->syncWithoutDetaching([$customerRole->id]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.customer')
            ->with('success', 'Đăng ký tài khoản thành viên GoSport thành công!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }

    private function normalizeVietnamesePhone(string $phone): string
    {
        $compactPhone = preg_replace('/[\s().-]+/u', '', trim($phone)) ?? trim($phone);

        if (preg_match('/^\+?84[0-9]{9,10}$/', $compactPhone)) {
            $digits = preg_replace('/\D+/', '', $compactPhone) ?? $compactPhone;

            return '0'.substr($digits, 2);
        }

        return $compactPhone;
    }

    private function landingRoute(User $user): string
    {
        return match (true) {
            $user->hasAnyRole(['super_admin', 'admin']) => 'dashboard.admin',
            $user->hasRole('owner') => 'dashboard.owner',
            $user->hasRole('staff') => 'dashboard.staff',
            $user->hasRole('coach') => 'dashboard.coach',
            default => 'explore',
        };
    }
}
