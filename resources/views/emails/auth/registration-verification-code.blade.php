<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ruang Alat</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:18px;padding:32px;border:1px solid #e5e7eb;">
        <div style="font-size:22px;font-weight:700;margin-bottom:12px;">Verifikasi Pendaftaran Akun</div>
        <p style="margin:0 0 12px;line-height:1.6;">Halo {{ $user->nama }},</p>
        <p style="margin:0 0 18px;line-height:1.6;">
            Gunakan kode berikut untuk menyelesaikan pendaftaran akun kamu. Kode ini berlaku selama 10 menit.
        </p>
        <div style="margin:0 0 20px;padding:18px 20px;background:#ecfeff;border-radius:14px;text-align:center;">
            <span style="font-size:32px;letter-spacing:10px;font-weight:800;color:#0f766e;">{{ $code }}</span>
        </div>
        <p style="margin:0;line-height:1.6;color:#6b7280;">
            Jika kamu tidak merasa melakukan pendaftaran, abaikan email ini.
        </p>
    </div>
</body>
</html>

