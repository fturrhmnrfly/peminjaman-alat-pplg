@php
    $notifCount = \App\Models\Notification::query()
        ->where('user_id', auth()->id())
        ->where('is_read', false)
        ->count();
@endphp

<a href="{{ route('notifications.index') }}"
   style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: #eef2ff; color: #1e3a8a; text-decoration: none;">
    <span style="font-size: 16px; line-height: 1;">&#128276;</span>
    @if($notifCount > 0)
        <span style="position: absolute; top: -2px; right: -2px; background: #ef4444; color: #fff; border-radius: 999px; font-size: 10px; padding: 2px 6px; line-height: 1;">
            {{ $notifCount }}
        </span>
    @endif
</a>
