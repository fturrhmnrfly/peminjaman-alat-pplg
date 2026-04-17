@php
    $user = auth()->user();
    $photoUrl = $user?->foto_profil ? asset($user->foto_profil) : null;
    $initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
@endphp

<a href="{{ route('profile.edit') }}"
   @if (request()->routeIs('profile.*')) aria-current="page" @endif
   style="display: inline-flex; align-items: center; gap: 10px; color: inherit; text-decoration: none;">
    <span style="width: 42px; height: 42px; border-radius: 50%; overflow: hidden; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb, #f8fafc); color: #334155; font-weight: 700; border: 2px solid rgba(255, 255, 255, 0.92); box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12); flex-shrink: 0;">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="Foto profil {{ $user->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
            {{ $initial }}
        @endif
    </span>
    <span style="display: flex; flex-direction: column; line-height: 1.2;">
        <span style="font-weight: 700;">{{ $user->nama }}</span>
        <span style="font-size: 12px; color: #6b7280;">Profil</span>
    </span>
</a>
