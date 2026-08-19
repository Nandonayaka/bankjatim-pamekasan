<!DOCTYPE html>
<html class="h-full bg-white" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke Sistem Manajemen Koperasi Jatim 007 — Pantau penjualan, kulaan, laba, dan stok secara real-time.">
    <title>Masuk — Koperasi Jatim 007</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="h-full font-['Poppins'] antialiased text-slate-800 bg-white">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- Left Side: Hero Image Banner (Using /images/login.jpg) --}}
    <div class="lg:w-1/2 relative bg-slate-900 min-h-[340px] lg:min-h-screen overflow-hidden flex flex-col justify-end p-8 lg:p-14">
        {{-- Background Image --}}
        <img src="{{ asset('images/login.jpg') }}" alt="Koperasi Jatim 007 Banner"
             class="absolute inset-0 w-full h-full object-cover object-center transform scale-105 transition-transform duration-1000">

        {{-- Dark Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent"></div>

        {{-- Hero Bottom Content --}}
        <div class="relative z-10 text-white max-w-xl">
            <h2 class="text-2xl lg:text-4xl font-semibold tracking-tight leading-tight mb-3 lg:mb-6">
                Sistem Manajemen Koperasi Jatim 007
            </h2>
            <p class="text-xs lg:text-sm text-slate-200 font-normal leading-relaxed mb-8 opacity-90">
                Kelola keindahan transaksi, histori kulaan, grafik penjualan, keuntungan bersih, dan persediaan stok barang secara otomatis & real-time.
            </p>
        </div>
    </div>

    {{-- Right Side: Login Form --}}
    <div class="lg:w-1/2 flex flex-col justify-between p-6 lg:p-12 bg-white">

        {{-- Topbar Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <img src="{{ asset('images/logo2.png') }}" alt="Bank Jatim Logo" class="h-11 lg:h-16 w-auto max-w-[260px] object-contain">
            </div>
        </div>

        {{-- Form Container --}}
        <div class="max-w-md w-full mx-auto my-auto py-6">

            <h1 class="text-2xl lg:text-3xl font-semibold text-slate-900 tracking-tight mb-1.5">
                Masuk ke Akun
            </h1>
            <p class="text-xs text-slate-500 font-medium mb-6">
                Masukkan email dan kata sandi Anda untuk mengakses dashboard.
            </p>

            {{-- Flash Alert Message --}}
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700 mb-5">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-700 mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="username">Username</label>
                    <input type="text" name="username" id="username"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-900 outline-none focus:border-[#C8102E] focus:ring-2 focus:ring-[#C8102E]/15 transition-all placeholder:text-slate-400"
                           placeholder="Masukkan username Anda"
                           value="{{ old('username') }}" required autofocus>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="password">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-900 outline-none focus:border-[#C8102E] focus:ring-2 focus:ring-[#C8102E]/15 transition-all placeholder:text-slate-400 pr-10"
                               placeholder="Masukkan kata sandi Anda"
                               required>
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700">
                            <svg class="w-4 h-4 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#C8102E] focus:ring-[#C8102E]">
                        <span class="text-slate-600 font-medium">Ingat saya</span>
                    </label>
                    <a href=""class="text-slate-600 font-medium hover:text-[#C8102E] transition-colors">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full bg-[#C8102E] hover:bg-[#a30a25] text-white font-bold text-xs py-3.5 px-6 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-2">
                    <span>Masuk Sekarang</span>
                    <svg class="w-4 h-4 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>

        </div>

        {{-- Footer --}}
        <div class="text-center text-[0.72rem] text-slate-400 font-medium">
            © 2026 Koperasi Jatim 007. All rights reserved.
        </div>

    </div>

</div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

</body>
</html>
