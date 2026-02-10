<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fb;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3a8a, #1e40af);
            color: white;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: #e5e7eb;
            padding: 12px 15px;
            border-radius: 10px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(250, 204, 21, 0.15);
            color: #fde68a;
        }

        .sidebar-footer {
            margin-top: auto;
            font-size: 13px;
            opacity: 0.8;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #facc15, #fde68a);
            color: #1e3a8a;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            max-width: 800px;
        }

        .header-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header-action h2 {
            font-size: 22px;
            color: #1f2937;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-group label span {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-control:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        .form-control.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 5px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #1e40af;
            color: white;
        }

        .btn-primary:hover {
            background: #1e3a8a;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .info-message {
            color: #6b7280;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">📚 Peminjaman Alat</div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
                <a href="{{ route('admin.user.index') }}">👥 User</a>
                <a href="{{ route('admin.kategori.index') }}">📂 Kategori</a>
                <a href="{{ route('admin.alat.index') }}" class="active">🛠️ Alat</a>
                <a href="{{ route('admin.log.index') }}">📋 Log Aktivitas</a>
                <a href="{{ route('admin.peminjaman.index') }}">📦 Data Peminjaman</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="logout-btn">🚪 Logout</button>
            </form>
            <div class="sidebar-footer">
                © {{ date('Y') }} Sistem Sekolah
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main">

            <!-- TOPBAR -->
            <div class="topbar">
                <strong>Tambah Alat</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content-card">

                <div class="header-action">
                    <h2>➕ Tambah Alat Baru</h2>
                </div>

                <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Nama Alat <span>*</span></label>
                        <input type="text" name="nama_alat" class="form-control @error('nama_alat') error @enderror" 
                               value="{{ old('nama_alat') }}" placeholder="Contoh: Mikroskop" required>
                        @error('nama_alat')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kategori <span>*</span></label>
                        <select name="kategori_id" class="form-control @error('kategori_id') error @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Jumlah <span>*</span></label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') error @enderror" 
                               value="{{ old('jumlah') }}" placeholder="Contoh: 10" min="0" required>
                        @error('jumlah')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kondisi <span>*</span></label>
                        <select name="kondisi" class="form-control @error('kondisi') error @enderror" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                        @error('kondisi')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') error @enderror" 
                                  placeholder="Masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Foto Alat (Opsional)</label>
                        <input type="file" name="foto" class="form-control @error('foto') error @enderror" accept="image/*">
                        <div class="info-message">📸 Format: JPG, PNG, GIF (Max 2MB)</div>
                        @error('foto')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Simpan</button>
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">↩️ Kembali</a>
                    </div>

                </form>

            </div>

        </main>
    </div>

</body>

</html>
