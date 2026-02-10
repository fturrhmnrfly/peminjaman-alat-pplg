<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="main">

        {{-- Topbar --}}
        <div class="topbar">
            <strong>@yield('page-title', 'Dashboard')</strong>

            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
                {{ auth()->user()->nama }}
            </div>
        </div>

        {{-- Page Content --}}
        @yield('content')

    </main>
</div>

</body>
</html>
