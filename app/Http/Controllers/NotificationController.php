<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->accessibleNotificationsQuery()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorizeNotification($notification);

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllAsRead()
    {
        $updated = $this->accessibleNotificationsQuery()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($updated === 0) {
            return back()->with('success', 'Semua notifikasi sudah dalam keadaan dibaca.');
        }

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }

    public function destroy(Notification $notification)
    {
        $this->authorizeNotification($notification);

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll()
    {
        $deleted = $this->accessibleNotificationsQuery()->delete();

        if ($deleted === 0) {
            return back()->with('success', 'Tidak ada notifikasi yang bisa dihapus.');
        }

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    private function accessibleNotificationsQuery()
    {
        $user = auth()->user();
        $role = $user->role ?? '';

        return Notification::query()
            ->where(function ($query) use ($user, $role) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($nestedQuery) use ($role) {
                        $nestedQuery->whereNull('user_id')->where('role', $role);
                    });
            });
    }

    private function authorizeNotification(Notification $notification): void
    {
        $user = auth()->user();
        $role = $user->role ?? '';

        $isAllowed = $notification->user_id === $user->id
            || ($notification->user_id === null && $notification->role === $role);

        if (! $isAllowed) {
            abort(403, 'Tidak diizinkan.');
        }
    }
}
