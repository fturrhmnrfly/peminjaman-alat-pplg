@php
    $role = auth()->user()->role ?? '';
    $notifCount = \App\Models\Notification::query()
        ->where(function ($query) use ($role) {
            $query->where('user_id', auth()->id())
                ->orWhere(function ($nestedQuery) use ($role) {
                    $nestedQuery->whereNull('user_id')->where('role', $role);
                });
        })
        ->where('is_read', false)
        ->count();
    $notifLabel = $notifCount > 99 ? '99+' : $notifCount;
@endphp

<a href="{{ route('notifications.index') }}"
   style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 999px; background: linear-gradient(135deg, #eef2ff, #dbeafe); color: #1e3a8a; text-decoration: none; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.16); transition: transform 0.18s ease, box-shadow 0.22s ease;"
   onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 14px 24px rgba(59, 130, 246, 0.22)'"
   onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 10px 20px rgba(59, 130, 246, 0.16)'">
    <span style="font-size: 16px; line-height: 1;">&#128276;</span>
    @if($notifCount > 0)
        <span style="position: absolute; top: -4px; right: -5px; min-width: 20px; height: 20px; background: #ef4444; color: #fff; border-radius: 999px; font-size: 10px; font-weight: 700; padding: 0 6px; line-height: 20px; text-align: center; border: 2px solid #fff;">
            {{ $notifLabel }}
        </span>
    @endif
</a>
