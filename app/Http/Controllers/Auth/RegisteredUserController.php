<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationVerificationCodeMail;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'nis' => ['required', 'string', 'max:30', 'unique:users,nis'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $nama = $request->input('nama');
        $email = $request->input('email');

        $username = $request->input('username') ?: $this->generateUniqueUsername($nama, $email);
        $verificationCode = $this->generateVerificationCode();

        $user = User::create([
            'nama' => $nama,
            'username' => $username,
            'nis' => $request->nis,
            'email' => $email,
            'email_verified_at' => null,
            'email_verification_code' => $verificationCode,
            'email_verification_expires_at' => now()->addMinutes(10),
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        event(new Registered($user));
        Mail::to($user->email)->send(new RegistrationVerificationCodeMail($user, $verificationCode));
        $request->session()->put('pending_registration_user_id', $user->id);

        return redirect()->route('register.verify.notice')->with('status', 'Kode verifikasi sudah dikirim ke email kamu.');
    }

    public function showVerificationForm(Request $request): View|RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('register')->withErrors([
                'email' => 'Sesi verifikasi tidak ditemukan. Silakan daftar kembali.',
            ]);
        }

        return view('auth.verify-register', ['user' => $user]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('register')->withErrors([
                'email' => 'Sesi verifikasi tidak ditemukan. Silakan daftar kembali.',
            ]);
        }

        if (! $user->email_verification_code || ! $user->email_verification_expires_at || $user->email_verification_expires_at->isPast()) {
            return back()->withErrors([
                'code' => 'Kode verifikasi sudah kedaluwarsa. Silakan kirim ulang kode baru.',
            ])->withInput();
        }

        if ($request->code !== $user->email_verification_code) {
            return back()->withErrors([
                'code' => 'Kode verifikasi tidak cocok.',
            ])->withInput();
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_expires_at' => null,
        ])->save();

        $request->session()->forget('pending_registration_user_id');
        Auth::login($user);
        $request->session()->regenerate();
        LogAktivitas::catat('Login', 'Auth', 'User berhasil login setelah verifikasi email');

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil dan email sudah terverifikasi.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('register')->withErrors([
                'email' => 'Sesi verifikasi tidak ditemukan. Silakan daftar kembali.',
            ]);
        }

        $verificationCode = $this->generateVerificationCode();

        $user->forceFill([
            'email_verification_code' => $verificationCode,
            'email_verification_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::to($user->email)->send(new RegistrationVerificationCodeMail($user, $verificationCode));

        return back()->with('status', 'Kode verifikasi baru sudah dikirim ke email kamu.');
    }

    private function generateUniqueUsername(string $nama, ?string $email = null): string
    {
        $base = $email
            ? Str::before($email, '@')
            : Str::slug($nama, '');

        $base = Str::lower($base ?: 'peminjam');
        $username = $base;
        $suffix = 1;

        while (User::where('username', $username)->exists()) {
            $username = "{$base}{$suffix}";
            $suffix++;
        }

        return $username;
    }

    private function generateVerificationCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function pendingUser(Request $request): ?User
    {
        $userId = $request->session()->get('pending_registration_user_id');

        if (! $userId) {
            return null;
        }

        return User::find($userId);
    }
}
