<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    @php
        $role = auth()->user()->role;
        $viteCss = match ($role) {
            'admin' => 'resources/css/admin-sidebar.css',
            'petugas' => 'resources/css/petugas-sidebar.css',
            default => 'resources/css/peminjam-sidebar.css',
        };
        $accent = match ($role) {
            'admin' => '#475569',
            'petugas' => '#35625f',
            default => '#4b6b52',
        };
        $accentSoft = match ($role) {
            'admin' => '#e2e8f0',
            'petugas' => '#d8ece8',
            default => '#dfeadf',
        };
        $sidebarComponent = match ($role) {
            'admin' => 'admin-sidebar',
            'petugas' => 'petugas-sidebar',
            default => 'peminjam-sidebar',
        };
        $photoUrl = $user->foto_profil ? asset($user->foto_profil) : null;
        $initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
        $isEditMode = request()->query('mode') === 'edit' || $errors->any();
        $profileFields = [
            ['label' => 'Username', 'value' => $user->username ?: '-'],
            ['label' => 'Email', 'value' => $user->email ?: '-'],
        ];

        if ($user->role === 'peminjam') {
            $profileFields[] = ['label' => 'Nomor Telepon', 'value' => $user->nomor_telepon ?: '-'];
            $profileFields[] = ['label' => 'NIS', 'value' => $user->nis ?: '-'];
            $profileFields[] = ['label' => 'Kelas', 'value' => $user->kelas ?: '-'];
            $profileFields[] = ['label' => 'Alamat', 'value' => $user->alamat ?: '-', 'full' => true];
        } else {
            $profileFields[] = ['label' => 'NIP', 'value' => $user->nip ?: '-'];
        }
    @endphp
    @vite([$viteCss, 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f3f4f6; color: #1f2937; }
        .layout { display: flex; min-height: 100vh; }
        .main { flex: 1; padding: 28px; }
        .topbar {
            background: #fff;
            border-radius: 20px;
            padding: 18px 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .topbar-title { font-size: 28px; font-weight: 700; }
        .topbar-subtitle { margin-top: 4px; color: #6b7280; font-size: 14px; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .profile-layout {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }
        .profile-card,
        .content-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }
        .profile-card { padding: 28px; text-align: center; }
        .avatar {
            width: 180px;
            height: 180px;
            margin: 0 auto 20px;
            border-radius: 28px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, {{ $accent }}, #111827);
            color: #fff;
            font-size: 56px;
            font-weight: 700;
        }
        .avatar img,
        .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
        .photo-caption {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
            margin-top: 8px;
        }
        .photo-side-actions {
            margin-top: 16px;
            display: grid;
            gap: 10px;
        }
        .stack { display: grid; gap: 24px; }
        .content-card { padding: 28px; }
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }
        .section-title { font-size: 24px; font-weight: 700; }
        .section-desc { margin-top: 6px; color: #6b7280; font-size: 14px; line-height: 1.6; }
        .status-badge {
            padding: 10px 14px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .status-badge.hidden { display: none; }
        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 24px;
        }
        .info-item {
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-item.full { grid-column: 1 / -1; }
        .info-label {
            display: block;
            margin-bottom: 8px;
            color: #6b7280;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .info-value {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.7;
            white-space: pre-line;
            word-break: break-word;
        }
        .profile-form { display: grid; gap: 22px; }
        .photo-upload {
            display: grid;
            grid-template-columns: 112px minmax(0, 1fr);
            gap: 18px;
            align-items: center;
            padding: 18px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }
        .photo-preview {
            width: 112px;
            height: 112px;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, {{ $accent }}, #111827);
            color: #fff;
            font-size: 36px;
            font-weight: 700;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }
        .form-group { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 700; color: #374151; }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            background: #fff;
            font-size: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: {{ $accent }};
            box-shadow: 0 0 0 4px rgba(71, 85, 105, 0.12);
        }
        textarea.form-control { min-height: 120px; resize: vertical; }
        .hint { color: #6b7280; font-size: 12px; line-height: 1.5; }
        .error-text { color: #b91c1c; font-size: 12px; }
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 160px;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }
        .btn-primary {
            border: none;
            cursor: pointer;
            background: {{ $accent }};
            color: #fff;
        }
        .btn-secondary {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
        }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 160px;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            background: #dc2626;
            color: #fff;
        }
        @media (max-width: 1100px) {
            .profile-layout { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .main { padding: 18px; }
            .topbar,
            .content-card,
            .profile-card { padding: 20px; }
            .topbar { flex-direction: column; align-items: flex-start; }
            .info-grid,
            .form-grid,
            .photo-upload { grid-template-columns: 1fr; }
            .info-item.full { grid-column: auto; }
            .photo-preview { margin: 0 auto; }
            .avatar {
                width: 140px;
                height: 140px;
                border-radius: 24px;
                font-size: 44px;
            }
            .actions,
            .btn-primary,
            .btn-secondary,
            .btn-danger { width: 100%; }
            .btn-primary,
            .btn-secondary,
            .btn-danger { min-width: 0; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <x-dynamic-component :component="$sidebarComponent" />
        <main class="main">
            <div class="topbar">
                <div>
                    <div class="topbar-title">Profil Saya</div>
                    <div class="topbar-subtitle">Lihat dan kelola informasi akun Anda di halaman ini.</div>
                </div>
                <div class="user-info">
                    <x-notification-bell />
                    <x-profile-shortcut />
                </div>
            </div>

            <div class="profile-layout">
                <aside class="profile-card">
                    <div class="avatar">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Foto profil {{ $user->nama }}">
                        @else
                            {{ $initial }}
                        @endif
                    </div>
                    <div class="photo-caption">
                        {{ $isEditMode ? 'Foto profil bisa diganti saat Anda mengedit profil.' : 'Klik Edit Profile jika ingin mengganti foto profil atau memperbarui informasi akun.' }}
                    </div>
                    @if($photoUrl)
                        <div class="photo-side-actions">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="nama" value="{{ $user->nama }}">
                                <input type="hidden" name="username" value="{{ $user->username }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                @if($user->role === 'peminjam')
                                    <input type="hidden" name="nomor_telepon" value="{{ $user->nomor_telepon }}">
                                    <input type="hidden" name="nis" value="{{ $user->nis }}">
                                    <input type="hidden" name="kelas" value="{{ $user->kelas }}">
                                    <input type="hidden" name="alamat" value="{{ $user->alamat }}">
                                @else
                                    <input type="hidden" name="nip" value="{{ $user->nip }}">
                                @endif
                                <input type="hidden" name="hapus_foto_profil" value="1">
                                <button type="submit" class="btn-danger">Hapus Foto Profile</button>
                            </form>
                        </div>
                    @endif
                </aside>

                <div class="stack">
                    <section class="content-card">
                        <div class="section-head">
                            <div>
                                <div class="section-title">{{ $isEditMode ? 'Edit Profil' : 'Informasi Akun' }}</div>
                                <div class="section-desc">
                                    {{ $isEditMode ? 'Perbarui data profil Anda lalu simpan perubahan.' : 'Informasi akun utama Anda ditampilkan di sini.' }}
                                </div>
                            </div>
                            <div class="status-badge {{ session('status') === 'profile-updated' ? '' : 'hidden' }}">Profil berhasil diperbarui</div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-error">{{ $errors->first() }}</div>
                        @endif

                        @if (! $isEditMode)
                            <div class="info-grid">
                                @foreach ($profileFields as $field)
                                    <div @class(['info-item', 'full' => $field['full'] ?? false])>
                                        <span class="info-label">{{ $field['label'] }}</span>
                                        <div class="info-value">{{ $field['value'] }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="actions" style="margin-top: 24px;">
                                <a href="{{ route('profile.edit', ['mode' => 'edit']) }}" class="btn-primary">Edit Profile</a>
                            </div>
                        @else
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
                                @csrf
                                @method('PATCH')

                                <div class="photo-upload">
                                    <div class="photo-preview" id="photoPreview">
                                        @if($photoUrl)
                                            <img src="{{ $photoUrl }}" alt="Preview foto profil {{ $user->nama }}">
                                        @else
                                            {{ $initial }}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="foto_profil">Foto Profil</label>
                                        <input id="foto_profil" name="foto_profil" type="file" class="form-control" accept=".jpg,.jpeg,.png">
                                        <div class="hint">Format `jpg`, `jpeg`, atau `png`. Maksimal 2 MB.</div>
                                        @error('foto_profil')<div class="error-text">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input id="nama" name="nama" type="text" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                                        @error('nama')<div class="error-text">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $user->username) }}" required>
                                        @error('username')<div class="error-text">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="error-text">{{ $message }}</div>@enderror
                                    </div>
                                    @if($user->role === 'peminjam')
                                        <div class="form-group">
                                            <label for="nomor_telepon">Nomor Telepon</label>
                                            <input id="nomor_telepon" name="nomor_telepon" type="text" class="form-control" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" required>
                                            @error('nomor_telepon')<div class="error-text">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="nis">NIS</label>
                                            <input id="nis" name="nis" type="text" class="form-control" value="{{ old('nis', $user->nis) }}" required>
                                            @error('nis')<div class="error-text">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="kelas">Kelas</label>
                                            <input id="kelas" name="kelas" type="text" class="form-control" value="{{ old('kelas', $user->kelas) }}" required>
                                            @error('kelas')<div class="error-text">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="form-group full">
                                            <label for="alamat">Alamat</label>
                                            <textarea id="alamat" name="alamat" class="form-control" required>{{ old('alamat', $user->alamat) }}</textarea>
                                            @error('alamat')<div class="error-text">{{ $message }}</div>@enderror
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="nip">NIP</label>
                                            <input id="nip" name="nip" type="text" class="form-control" value="{{ old('nip', $user->nip) }}" required>
                                            @error('nip')<div class="error-text">{{ $message }}</div>@enderror
                                        </div>
                                    @endif
                                </div>

                                <div class="actions">
                                    <a href="{{ route('profile.edit') }}" class="btn-secondary">Batal</a>
                                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        @endif
                    </section>

                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('foto_profil');
            var preview = document.getElementById('photoPreview');
            if (!input || !preview) {
                return;
            }

            function bindPreview(fileInput, target) {
                if (!fileInput || !target) {
                    return;
                }

                fileInput.addEventListener('change', function(event) {
                    var file = event.target.files && event.target.files[0];

                    if (!file) {
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(loadEvent) {
                        target.innerHTML = '<img src="' + loadEvent.target.result + '" alt="Preview foto profil">';
                    };
                    reader.readAsDataURL(file);
                });
            }

            bindPreview(input, preview);
        });
    </script>
</body>
</html>
