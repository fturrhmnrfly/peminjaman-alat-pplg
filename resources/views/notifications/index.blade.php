<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    @php
        $role = auth()->user()->role ?? '';
        $sidebarCss = 'resources/css/admin-sidebar.css';
        if ($role === 'petugas') $sidebarCss = 'resources/css/petugas-sidebar.css';
        if ($role === 'peminjam') $sidebarCss = 'resources/css/peminjam-sidebar.css';
    @endphp
    @vite([$sidebarCss, 'resources/js/app.js'])

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

        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
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

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .notif-list {
            display: grid;
            gap: 12px;
        }

        .notif-item {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
            background: #f9fafb;
        }

        .notif-item.unread {
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .notif-title {
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .notif-meta {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .notif-actions {
            margin-top: 8px;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-primary {
            background: #1e40af;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="layout">
        @if($role === 'admin')
            <x-admin-sidebar />
        @elseif($role === 'petugas')
            <x-petugas-sidebar />
        @else
            <x-peminjam-sidebar />
        @endif

        <main class="main">
            <div class="topbar">
                <strong>Notifikasi</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama ?? '', 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama ?? '' }}</span>
                </div>
            </div>

            <div class="content-card">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="notif-list">
                    @forelse($notifications as $notif)
                        <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                            <div class="notif-title">{{ $notif->title }}</div>
                            <div class="notif-meta">{{ optional($notif->created_at)->format('d/m/Y H:i') ?? '-' }} WIB</div>
                            <div>{{ $notif->message }}</div>
                            @if(! $notif->is_read)
                                <div class="notif-actions">
                                    <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Tandai Dibaca</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="color: #9ca3af;">Belum ada notifikasi.</div>
                    @endforelse
                </div>

                <div class="pagination">
                    {{ $notifications->links() }}
                </div>
            </div>
        </main>
    </div>
</body>

</html>
