<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Peminjaman PPLG') }}</title>
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
        }

        .login-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 20px;
        }

        .login-container {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        .login-illustration {
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

        .login-illustration h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .login-illustration p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-icon {
            font-size: 80px;
            margin-bottom: 30px;
        }

        .login-form {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h1 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-form > p {
            color: #6b7280;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
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

        .error-message {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login-button {
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
            margin-top: 10px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 118, 110, 0.3);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }

        .register-link a {
            color: #0f766e;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
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

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-illustration {
                padding: 40px 20px;
            }

            .login-illustration h2 {
                font-size: 24px;
            }

            .login-form {
                padding: 40px 25px;
            }

            .login-form h1 {
                font-size: 24px;
            }

            .login-icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <a href="/" class="back-link">← Kembali</a>

    <div class="login-wrapper">
        <div class="login-container">
            <!-- ILLUSTRATION SIDE -->
            <div class="login-illustration">
                <div class="login-icon">📚</div>
                <h2>{{ config('', 'Peminjaman PPLG') }}</h2>
                <p>Sistem peminjaman alat pembelajaran yang modern dan efisien untuk sekolah Anda</p>
            </div>

            <!-- FORM SIDE -->
            <div class="login-form">
                <h1>Masuk Akun Anda</h1>
                <p>Gunakan username dan password untuk login</p>

                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            placeholder="Masukkan username Anda"
                            required 
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Masukkan password Anda"
                                required
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                👁️
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-button">Login Sekarang</button>
                </form>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
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
