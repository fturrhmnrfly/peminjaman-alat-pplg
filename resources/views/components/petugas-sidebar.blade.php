<aside class="sidebar">
    @php
        $notifCount = \App\Models\Notification::query()
            ->where('user_id', auth()->id())
            ->orWhere(function ($query) {
                $query->whereNull('user_id')->where('role', 'petugas');
            })
            ->where('is_read', false)
            ->count();
    @endphp
    <div class="sidebar-brand">📚 Peminjaman Alat</div>

    <nav class="sidebar-menu">
        <a href="{{ route('notifications.index') }}" @class(['active' => request()->routeIs('notifications.*')])>Notifikasi @if($notifCount > 0)<strong>({{ $notifCount }})</strong>@endif</a>
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
        © {{ date('Y') }} Peminjaman Alat PPLG
    </div>
</aside>
