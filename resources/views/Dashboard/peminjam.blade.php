<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Peminjam</title>
</head>
<body>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Peminjam') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Selamat datang, {{ auth()->user()->nama }}!</h3>
                    
                    <div class="mt-6">
                        <a href="{{ route('peminjaman.index') }}" class="inline-block p-4 bg-indigo-100 dark:bg-indigo-900 rounded-lg hover:bg-indigo-200">
                            <h4 class="font-semibold text-indigo-900 dark:text-indigo-100">Ajukan Peminjaman</h4>
                            <p class="text-sm text-indigo-700 dark:text-indigo-300">Pinjam alat pembelajaran</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

</body>
</html>
