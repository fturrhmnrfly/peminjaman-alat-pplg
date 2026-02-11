<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat</title>
    @vite(['resources/css/admin-sidebar.css', 'resources/js/app.js'])

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

        .current-foto {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="layout">

        <!-- SIDEBAR - DARI COMPONENT -->
        <x-admin-sidebar />

        <!-- MAIN -->
        <main class="main">

            <!-- TOPBAR -->
            <div class="topbar">
                <strong>Edit Alat</strong>
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
                    <h2>✏️ Edit Alat</h2>
                </div>

                <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Alat <span>*</span></label>
                        <input type="text" name="nama_alat" class="form-control @error('nama_alat') error @enderror" 
                               value="{{ old('nama_alat', $alat->nama_alat) }}" placeholder="Contoh: Mikroskop" required>
                        @error('nama_alat')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kategori <span>*</span></label>
                        <select name="kategori_id" class="form-control @error('kategori_id') error @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                               value="{{ old('jumlah', $alat->jumlah) }}" placeholder="Contoh: 10" min="0" required>
                        @error('jumlah')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kondisi <span>*</span></label>
                        <select name="kondisi" class="form-control @error('kondisi') error @enderror" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baik" {{ old('kondisi', $alat->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi', $alat->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="hilang" {{ old('kondisi', $alat->kondisi) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                        @error('kondisi')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') error @enderror" 
                                  placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $alat->keterangan) }}</textarea>
                        @error('keterangan')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Foto Alat (Opsional)</label>
                        <input type="file" name="foto" class="form-control @error('foto') error @enderror" accept="image/*">
                        <div class="info-message">📸 Format: JPG, PNG, GIF (Max 2MB) | Kosongkan jika tidak ingin mengubah foto</div>
                        @error('foto')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        
                        @if($alat->foto)
                        <div style="margin-top: 10px;">
                            <strong>Foto Saat Ini:</strong><br>
                            @php
                                $fotoValue = $alat->foto;
                                $fotoPath = \Illuminate\Support\Str::startsWith($fotoValue, ['alat/', 'public/alat/'])
                                    ? $fotoValue
                                    : 'alat/' . $fotoValue;
                                $fotoPath = \Illuminate\Support\Str::startsWith($fotoPath, 'public/')
                                    ? \Illuminate\Support\Str::after($fotoPath, 'public/')
                                    : $fotoPath;
                            @endphp
                            @php
                                /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
                                $publicDisk = \Illuminate\Support\Facades\Storage::disk('public');
                            @endphp
                            <img src="{{ $publicDisk->url($fotoPath) }}" alt="{{ $alat->nama_alat }}" class="current-foto">
                        </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Update</button>
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">↩️ Kembali</a>
                    </div>

                </form>

            </div>

        </main>
    </div>

</body>

</html>
