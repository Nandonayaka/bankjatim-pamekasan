@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan informasi & statistik koperasi')

@section('content')

{{-- Banner Welcome Card --}}
<div class="relative mt-1 sm:mt-2 mb-5 sm:mb-6 rounded-2xl overflow-hidden shadow-xs">
    <img src="{{ asset('images/particle.png') }}" alt="Banner" class="w-full h-32 sm:h-44 md:h-48 object-cover">
    <div class="absolute inset-0 flex flex-col justify-center px-4 sm:px-8 lg:px-10 text-white bg-black/10">
        <h2 class="text-lg sm:text-2xl lg:text-3xl font-semibold tracking-tight leading-tight">
            Selamat datang , {{ Auth::user()->name ?? 'Admin' }}
        </h2>
        <p class="text-[0.7rem] sm:text-sm lg:text-base font-normal mt-1 sm:mt-1.5 opacity-95">
            Kelola koperasi dengan mudah dan efisien
        </p>
    </div>
</div>

{{-- Top Row: Month Filter --}}
<div class="flex items-center justify-between mb-5 sm:mb-6 flex-wrap gap-2.5 sm:gap-3">
    <div class="text-xs sm:text-sm font-semibold text-slate-600">
        Periode Laporan: <span class="font-bold text-slate-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</span>
    </div>
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 w-full sm:w-auto">
        <label for="month-filter" class="text-xs font-semibold text-slate-500 shrink-0">Filter Bulan:</label>
        <select name="month" id="month-filter" onchange="this.form.submit()"
                class="w-full sm:w-auto bg-white border border-slate-300 rounded-xl px-3 sm:px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-[#C8102E] shadow-xs cursor-pointer">
            @foreach($monthOptions as $opt)
                <option value="{{ $opt['value'] }}" {{ $month === $opt['value'] ? 'selected' : '' }}>
                    {{ $opt['label'] }}
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- 4 KPI Cards (Matching Image Mockup Exactly) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 mb-5 sm:mb-6">

    {{-- Card 1: Total Kulaan --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="text-[#f59e0b] font-regular text-xs tracking-wide">Total kulaan</div>
        <div class="text-[#f59e0b] font-semibold text-xl sm:text-2xl lg:text-3xl mt-1 tracking-tight">
            RP {{ number_format($totalKulaan, 0, ',', '.') }}
        </div>
        <div class="text-[0.7rem] text-slate-400 font-medium mt-3 sm:mt-4">Bulan ini</div>
        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
            <div class="bg-[#f59e0b] h-full rounded-full transition-all duration-500" style="width: {{ $totalKulaan > 0 ? '75%' : '0%' }}"></div>
        </div>
    </div>

    {{-- Card 2: Barang Terjual --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="text-[#f59e0b] font-regular text-xs tracking-wide">Barang terjual</div>
        <div class="text-[#f59e0b] font-semibold text-xl sm:text-2xl lg:text-3xl mt-1 tracking-tight">
            {{ number_format($totalTerjual, 0, ',', '.') }} <span class="text-xs font-semibold text-[#f59e0b]">PCS</span>
        </div>
        <div class="text-[0.7rem] text-slate-400 font-medium mt-3 sm:mt-4">Bulan ini</div>
        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
            <div class="bg-[#f59e0b] h-full rounded-full transition-all duration-500" style="width: {{ $totalTerjual > 0 ? '60%' : '0%' }}"></div>
        </div>
    </div>

    {{-- Card 3: Total Laba --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="text-[#f59e0b] font-regular text-xs tracking-wide">Total laba</div>
        <div class="text-[#f59e0b] font-semibold text-xl sm:text-2xl lg:text-3xl mt-1 tracking-tight">
            RP {{ number_format($totalLaba, 0, ',', '.') }}
        </div>
        <div class="text-[0.7rem] text-slate-400 font-medium mt-3 sm:mt-4">Bulan ini</div>
        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
            <div class="bg-[#f59e0b] h-full rounded-full transition-all duration-500" style="width: {{ $totalLaba > 0 ? '50%' : '0%' }}"></div>
        </div>
    </div>

    {{-- Card 4: Stok Barang --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="text-[#f59e0b] font-regular text-xs tracking-wide">stok barang</div>
        <div class="text-[#f59e0b] font-semibold text-xl sm:text-2xl lg:text-3xl mt-1 tracking-tight">
            {{ number_format($totalStok, 0, ',', '.') }} <span class="text-xs font-semibold text-[#f59e0b]">PCS</span>
        </div>
        <div class="text-[0.7rem] text-slate-400 font-medium mt-3 sm:mt-4">Bulan ini</div>
        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
            <div class="bg-[#f59e0b] h-full rounded-full transition-all duration-500" style="width: {{ $totalStok > 0 ? '80%' : '0%' }}"></div>
        </div>
    </div>

</div>

{{-- Charts Row: 2 Column Layout (Matching Image Mockup) --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6">

    {{-- Left Chart: BULANAN (Gold/Orange Border Card) --}}
    <div class="lg:col-span-7 bg-white border-2 border-[#f59e0b]/60 rounded-2xl p-4 sm:p-6 shadow-xs flex flex-col justify-between">
        <div>
            <h3 class="text-sm font-bold text-[#f59e0b] tracking-wider uppercase mb-3 sm:mb-4">BULANAN</h3>
            <div class="h-56 sm:h-72 w-full">
                <canvas id="bulananChart"></canvas>
            </div>
        </div>
        <div class="flex items-center justify-center gap-2 mt-3 sm:mt-4 text-xs font-medium text-slate-600">
            <span class="w-3 h-3 rounded-full bg-[#f59e0b] inline-block border-2 border-white shadow-xs"></span>
            <span>Penjualan (%)</span>
        </div>
    </div>

    {{-- Right Chart: Proporsi Bulanan (Donut Chart) --}}
    <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-xs flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800 mb-3 sm:mb-4">Proporsi bulanan</h3>
            <div class="relative h-56 sm:h-72 flex items-center justify-center">
                {{-- Donut Center Percentage (Placed BEHIND canvas z-0 so tooltips render on top z-10) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-0">
                    <span class="text-2xl sm:text-3xl font-extrabold {{ $isZero ? 'text-slate-400' : 'text-[#f59e0b]' }} select-none">
                        {{ $isZero ? '0%' : '100%' }}
                    </span>
                </div>
                <canvas id="proporsiChart" class="relative z-10 pointer-events-auto"></canvas>
            </div>
        </div>

        {{-- Callout Legend Badges --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-4 pt-4 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b] shrink-0"></span>
                <span class="text-slate-600 font-medium truncate">Total kulaan: <strong class="text-slate-900">{{ $propKulaan }}%</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#d97706] shrink-0"></span>
                <span class="text-slate-600 font-medium truncate">Barang terjual: <strong class="text-slate-900">{{ $propTerjual }}%</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#eab308] shrink-0"></span>
                <span class="text-slate-600 font-medium truncate">Total laba: <strong class="text-slate-900">{{ $propLaba }}%</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#cca028] shrink-0"></span>
                <span class="text-slate-600 font-medium truncate">Stok barang: <strong class="text-slate-900">{{ $propStok }}%</strong></span>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ── 1. Bulanan Line Chart ──
        const ctxLine = document.getElementById('bulananChart').getContext('2d');

        // Create gradient fill
        const gradient = ctxLine.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(245, 158, 11, 0.35)');
        gradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

        const monthsAbbr = @json($monthsAbbr);
        const chartSalesData = @json($chartSalesData);

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: monthsAbbr,
                datasets: [{
                    label: 'Penjualan (RP)',
                    data: chartSalesData,
                    borderColor: '#f59e0b',
                    borderWidth: 2.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 3.5,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'RP ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10, family: 'Poppins' } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 10, family: 'Poppins' } }
                    }
                }
            }
        });

        // ── 2. Donut Chart Proportions (Real-time per month with Full Grey fallback when 0%) ──
        const ctxDonut = document.getElementById('proporsiChart').getContext('2d');
        const isZero = @json($isZero);
        const propKulaan  = {{ $propKulaan }};
        const propTerjual = {{ $propTerjual }};
        const propLaba    = {{ $propLaba }};
        const propStok    = {{ $propStok }};

        const donutLabels = isZero
            ? ['Belum ada data']
            : ['Total kulaan', 'Barang terjual', 'Total laba', 'Stok barang'];

        const donutValues = isZero
            ? [100]
            : [propKulaan, propTerjual, propLaba, propStok];

        const donutColors = isZero
            ? ['#e2e8f0']
            : ['#f59e0b', '#d97706', '#eab308', '#cca028'];

        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutValues,
                    backgroundColor: donutColors,
                    borderWidth: 0,
                    borderRadius: isZero ? 0 : 10,
                    spacing: isZero ? 0 : 6,
                    hoverOffset: isZero ? 0 : 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return isZero ? 'Belum ada transaksi bulan ini' : context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
