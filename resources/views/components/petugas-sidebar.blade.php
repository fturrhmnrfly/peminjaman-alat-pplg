<aside class="sidebar">
    <div class="sidebar-brand">📚 Ruang Alat</div>

    <nav class="sidebar-menu">
        <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>🏠 Dashboard</a>
        <a href="{{ route('verifikasi') }}" @class(['active' => request()->routeIs('verifikasi')])>✅ Menyetujui Peminjaman</a>
        <a href="{{ route('petugas.laporan') }}" @class(['active' => request()->routeIs('petugas.laporan*')])>🧾 Mencetak Laporan</a>
        <a href="{{ route('petugas.pengembalian') }}" @class(['active' => request()->routeIs('petugas.pengembalian*')])>📦 Memantau Pengembalian</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="logout-btn">🚪 Logout</button>
    </form>

    <div class="sidebar-footer">
        © {{ date('Y') }} Ruang Alat
    </div>
</aside>
