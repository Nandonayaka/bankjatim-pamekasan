<!DOCTYPE html>
<html class="h-full bg-slate-50" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Manajemen Koperasi Jatim 007 — Pantau kulaan, penjualan, laba, dan stok secara real-time.">
    <title>@yield('title', 'Dashboard') — Koperasi Jatim 007</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @stack('head')
</head>
<body class="h-full font-['Poppins'] antialiased text-slate-800 bg-slate-50 flex">
<div class="flex w-full min-h-screen">

    {{-- ── Sidebar ── --}}
    @include('layouts.partials.sidebar')

    {{-- ── Main Area ── --}}
    <div class="flex-1 lg:ml-[280px] flex flex-col min-h-screen w-full transition-all">

        {{-- Topbar --}}
        <header class="h-20 bg-slate-50 flex items-center justify-between px-5 lg:px-9 pt-4 pb-0 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                {{-- Mobile Hamburger --}}
                <button type="button" class="p-2 text-slate-600 hover:text-slate-900 lg:hidden rounded-lg hover:bg-slate-200/60" id="mobile-toggle-btn" aria-label="Toggle Sidebar">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div class="flex flex-col justify-center">
                    <h1 class="text-xl lg:text-2xl font-semibold text-slate-900 tracking-tight leading-none">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 font-medium mt-0.5 hidden sm:block">@yield('page_subtitle', 'Ringkasan informasi koperasi')</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-slate-900 leading-tight">{{ Auth::user()->name ?? 'Jaka kusuma A' }}</div>
                        <div class="text-[0.7rem] text-slate-500 font-medium">Admin</div>
                    </div>
                    <div class="w-9.5 h-9.5 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#cbd5e1" class="w-9 h-9">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block ml-1">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Keluar / Logout">
                            <svg class="w-4.5 h-4.5 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 px-4 lg:px-9 py-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="flex items-center gap-2.5 px-4.5 py-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-300 text-emerald-700 mb-5 shadow-xs transition-all duration-300" id="flash-alert">
                    <svg class="w-4 h-4 stroke-current fill-none shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-2.5 px-4.5 py-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-300 text-rose-700 mb-5 shadow-xs transition-all duration-300" id="flash-alert">
                    <svg class="w-4 h-4 stroke-current fill-none shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

{{-- Mobile Sidebar Toggle JS --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggleBtn = document.getElementById('mobile-toggle-btn');

        function openSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            backdrop?.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar?.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
        }

        toggleBtn?.addEventListener('click', openSidebar);
        backdrop?.addEventListener('click', closeSidebar);
    });

    (function() {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.4s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 400);
            }, 4000);
        }
    })();
</script>

@stack('scripts')
</body>
</html>
