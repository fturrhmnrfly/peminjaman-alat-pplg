<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'nama' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'nis' => ['nullable', 'string', 'max:30', 'unique:users,nis'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:peminjam'],
        ]);

        $nama = $request->input('nama', $request->input('name'));
        $email = $request->input('email');

        if (! $nama) {
            $request->validate([
                'name' => ['required'],
            ]);
        }

        $username = $request->input('username') ?: $this->generateUniqueUsername($nama, $email);

        // Registrasi publik hanya untuk peminjam.
        $user = User::create([
            'nama' => $nama,
            'username' => $username,
            'nis' => $request->nis,
            'email' => $email ?: "{$username}@local.test",
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
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
}
