<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - {{ config('app.name', 'Peminjaman PPLG') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
        }

        .register-wrapper {
            width: 100%;
            max-width: 900px;
        }

        .register-container {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        .register-illustration {
            flex: 1;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .register-illustration h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .register-illustration p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .register-icon {
            font-size: 80px;
            margin-bottom: 30px;
        }

        .register-form {
            flex: 1;
            padding: 60px 50px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .register-form h1 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .register-form > p {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .form-error {
            color: #991b1b;
            font-size: 12px;
            margin-top: 5px;
        }

        .error-message {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .register-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 118, 110, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }

        .login-link a {
            color: #0f766e;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .back-link {
            position: fixed;
            top: 20px;
            left: 20px;
            color: #0f766e;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .back-link:hover {
            gap: 12px;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .register-illustration {
                padding: 40px 20px;
            }

            .register-illustration h2 {
                font-size: 24px;
            }

            .register-form {
                padding: 40px 25px;
            }

            .register-form h1 {
                font-size: 24px;
            }

            .register-icon {
                font-size: 60px;
            }
        }

        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper input {
            width: 100%;
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 5px 8px;
            transition: 0.2s;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            transform: scale(1.1);
        }

        .toggle-password:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <a href="/" class="back-link">← Kembali</a>

    <div class="register-wrapper">
        <div class="register-container">
            <!-- ILLUSTRATION SIDE -->
            <div class="register-illustration">
                <div class="register-icon">📝</div>
                <h2>{{ config('', 'Peminjaman PPLG') }}</h2>
                <p>Daftar sebagai peminjam dan nikmati kemudahan peminjaman alat secara digital</p>
            </div>

            <!-- FORM SIDE -->
            <div class="register-form">
                <h1>Daftar Akun Baru</h1>
                <p>Isi data di bawah untuk membuat akun peminjam</p>

                @if ($errors->any())
                    <div class="error-message">
                        Terjadi kesalahan, silakan periksa kembali
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                        @error('nama')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            placeholder="Buat username unik"
                            required
                        >
                        @error('username')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nis">NIS (Nomor Induk Siswa)</label>
                        <input 
                            type="text" 
                            id="nis" 
                            name="nis" 
                            value="{{ old('nis') }}"
                            placeholder="Masukkan NIS Anda"
                            required
                        >
                        @error('nis')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Minimal 8 karakter"
                                required
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                👁️
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Ulangi password"
                                required
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                👁️
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="register-button">Daftar Sekarang</button>
                </form>

                <div class="login-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = event.target.closest('.toggle-password');
            
            if (field.type === 'password') {
                field.type = 'text';
                button.textContent = '🙈';
            } else {
                field.type = 'password';
                button.textContent = '👁️';
            }
        }
    </script>
</body>
</html>
