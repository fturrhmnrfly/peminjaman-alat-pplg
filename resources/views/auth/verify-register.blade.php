<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Alat</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(255,255,255,0.96);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.14);
            padding: 36px 32px;
        }
        h1 { font-size: 28px; color: #111827; margin-bottom: 10px; }
        .desc { color: #6b7280; font-size: 14px; line-height: 1.7; margin-bottom: 24px; }
        .email-box {
            margin-bottom: 22px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #f0fdfa;
            color: #0f766e;
            font-size: 14px;
            font-weight: 600;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        label {
            display: block;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 20px;
            letter-spacing: 8px;
            text-align: center;
            font-weight: 700;
            margin-bottom: 8px;
        }
        input:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }
        .hint { color: #6b7280; font-size: 12px; margin-bottom: 20px; }
        .error { color: #991b1b; font-size: 12px; margin-top: 6px; margin-bottom: 12px; }
        .button {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        .button-primary {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
            margin-bottom: 12px;
        }
        .button-secondary { background: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Verifikasi Email</h1>
        <p class="desc">Masukkan kode 6 digit yang sudah dikirim ke email berikut untuk menyelesaikan pendaftaran akun.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="email-box">{{ $user->email }}</div>

        <form method="POST" action="{{ route('register.verify.store') }}">
            @csrf
            <label for="code">Kode Verifikasi</label>
            <input type="text" id="code" name="code" inputmode="numeric" maxlength="6" value="{{ old('code') }}" placeholder="000000" required autofocus>
            <div class="hint">Kode berlaku 10 menit. Jika belum masuk, kirim ulang kode verifikasi.</div>
            @error('code')
                <div class="error">{{ $message }}</div>
            @enderror
            <button type="submit" class="button button-primary">Verifikasi dan Masuk</button>
        </form>

        <form method="POST" action="{{ route('register.verify.resend') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="button button-secondary">Kirim Ulang Kode</button>
        </form>
    </div>
</body>
</html>

