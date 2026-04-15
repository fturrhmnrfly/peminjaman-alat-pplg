<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['nullable', 'string'],
            'username' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $request->input('login')
            ?: $request->input('email')
            ?: $request->input('username');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($login && Auth::attempt([$field => $login, 'password' => $request->password])) {
            $user = Auth::user();

            if (! $user?->email_verified_at) {
                Auth::logout();
                $request->session()->put('pending_registration_user_id', $user->id);

                return redirect()->route('register.verify.notice')->withErrors([
                    'username' => 'Email akun ini belum diverifikasi. Masukkan kode verifikasi yang dikirim ke email kamu.',
                ]);
            }

            $request->session()->regenerate();
            LogAktivitas::catat('Login', 'Auth', 'User berhasil login');

            // Redirect ke dashboard (yang akan menampilkan view sesuai role)
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            LogAktivitas::catat('Logout', 'Auth', "User {$user->nama} logout");
        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
