<aside class="sidebar">
    @php
        $notifCount = \App\Models\Notification::query()
            ->where('user_id', auth()->id())
            ->orWhere(function ($query) {
                $query->whereNull('user_id')->where('role', 'peminjam');
            })
            ->where('is_read', false)
            ->count();
    @endphp
    <div class="sidebar-brand">📚 Peminjaman Alat</div>

    <nav class="sidebar-menu">
        <a href="{{ route('notifications.index') }}" @class(['active' => request()->routeIs('notifications.*')])>Notifikasi @if($notifCount > 0)<strong>({{ $notifCount }})</strong>@endif</a>
        <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>🏠 Dashboard</a>
        <a href="{{ route('peminjam.alat.index') }}" @class(['active' => request()->routeIs('peminjam.alat.*')])>🧰 Daftar Alat</a>
        <a href="{{ route('peminjaman.index') }}" @class(['active' => request()->routeIs('peminjaman.*')])>📝 Ajukan Peminjaman</a>
        <a href="{{ route('peminjam.pengembalian.index') }}" @class(['active' => request()->routeIs('peminjam.pengembalian.*')])>📦 Pengembalian</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="logout-btn">🚪 Logout</button>
    </form>

    <div class="sidebar-footer">
        © {{ date('Y') }} Peminjaman Alat PPLG
    </div>
</aside>
