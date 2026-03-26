<aside class="sidebar">
    <div class="sidebar-brand">📚 Peminjaman Alat</div>

    <nav class="sidebar-menu">
        <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>🏠 Dashboard</a>
        <a href="{{ route('admin.user.index') }}" @class(['active' => request()->routeIs('admin.user.*')])>👥 User</a>
        <a href="{{ route('admin.kategori.index') }}" @class(['active' => request()->routeIs('admin.kategori.*')])>📂 Kategori</a>
        <a href="{{ route('admin.alat.index') }}" @class(['active' => request()->routeIs('admin.alat.*')])>🛠️ Alat</a>
        <a href="{{ route('admin.log.index') }}" @class(['active' => request()->routeIs('admin.log.*')])>📋 Log Aktivitas</a>
        <a href="{{ route('admin.peminjaman.index') }}" @class(['active' => request()->routeIs('admin.peminjaman.*')])>📦 Data Peminjaman</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="logout-btn">🚪 Logout</button>
    </form>

    <div class="sidebar-footer">
        &copy; {{ date('Y') }} Peminjaman Alat PPLG
    </div>
</aside>
