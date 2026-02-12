<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role ?? '';

        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->orWhere(function ($query) use ($role) {
                $query->whereNull('user_id')->where('role', $role);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        $user = auth()->user();
        $role = $user->role ?? '';

        $isAllowed = $notification->user_id === $user->id
            || ($notification->user_id === null && $notification->role === $role);

        if (! $isAllowed) {
            abort(403, 'Tidak diizinkan.');
        }

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }
}
