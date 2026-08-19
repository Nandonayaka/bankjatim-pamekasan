<nav class="w-[280px] min-h-screen bg-[#C8102E] rounded-tr-[16px] rounded-br-[16px] fixed top-0 left-0 bottom-0 z-50 flex flex-col shadow-2xl transition-transform duration-300 -translate-x-full lg:translate-x-0" id="sidebar">

    {{-- Brand Header --}}
    <div class="px-6 pt-8 pb-6">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Bank Jatim Emblem Logo" class="w-15 h-15 object-contain shrink-0">
            </div>
            <div class="leading-tight text-white">
                <div class="text-[16px] font-semibold tracking-wider">KOPERASI</div>
                <div class="text-[16px] font-semibold tracking-tight">Jatim 007</div>
            </div>
        </div>
    </div>

    {{-- Navigation List --}}
    <div class="flex-1 pt-2 pb-6 flex flex-col gap-2 overflow-y-auto">

        {{-- Dashboard --}}
        @php $isDash = request()->routeIs('dashboard'); @endphp
        <div class="relative flex items-center">
            @if($isDash)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-[40px] bg-[#D4AF37] z-10"></span>
            @endif
            <a href="{{ route('dashboard') }}"
               class="w-[242px] ml-[14px] h-[40px] px-4 rounded-tl-[100px] rounded-bl-[100px] rounded-tr-[4px] rounded-br-[4px] flex items-center gap-3.5 text-sm font-semibold transition-all duration-200 {{ $isDash ? 'bg-[#D4AF37] text-white shadow-md' : 'text-white/90 hover:bg-white/10 hover:text-white' }}"
               id="nav-dashboard">
                <svg class="w-5 h-5 shrink-0 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Dashboard
            </a>
        </div>

        {{-- Purchase Price --}}
        @php $isPur = request()->routeIs('purchases.*'); @endphp
        <div class="relative flex items-center">
            @if($isPur)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-[40px] bg-[#D4AF37] z-10"></span>
            @endif
            <a href="{{ route('purchases.index') }}"
               class="w-[242px] ml-[14px] h-[40px] px-4 rounded-tl-[100px] rounded-bl-[100px] rounded-tr-[4px] rounded-br-[4px] flex items-center gap-3.5 text-sm font-semibold transition-all duration-200 {{ $isPur ? 'bg-[#D4AF37] text-white shadow-md' : 'text-white/90 hover:bg-white/10 hover:text-white' }}"
               id="nav-purchases">
                <svg class="w-5 h-5 shrink-0 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/>
                </svg>
                Purchase Price
            </a>
        </div>

        {{-- Selling Price --}}
        @php $isSal = request()->routeIs('sales.*'); @endphp
        <div class="relative flex items-center">
            @if($isSal)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-[40px] bg-[#D4AF37] z-10"></span>
            @endif
            <a href="{{ route('sales.index') }}"
               class="w-[242px] ml-[14px] h-[40px] px-4 rounded-tl-[100px] rounded-bl-[100px] rounded-tr-[4px] rounded-br-[4px] flex items-center gap-3.5 text-sm font-semibold transition-all duration-200 {{ $isSal ? 'bg-[#D4AF37] text-white shadow-md' : 'text-white/90 hover:bg-white/10 hover:text-white' }}"
               id="nav-sales">
                <svg class="w-5 h-5 shrink-0 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                Selling price
            </a>
        </div>

        {{-- Debit --}}
        @php $isDeb = request()->routeIs('debit.*'); @endphp
        <div class="relative flex items-center">
            @if($isDeb)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-[40px] bg-[#D4AF37] z-10"></span>
            @endif
            <a href="{{ route('debit.index') }}"
               class="w-[242px] ml-[14px] h-[40px] px-4 rounded-tl-[100px] rounded-bl-[100px] rounded-tr-[4px] rounded-br-[4px] flex items-center gap-3.5 text-sm font-semibold transition-all duration-200 {{ $isDeb ? 'bg-[#D4AF37] text-white shadow-md' : 'text-white/90 hover:bg-white/10 hover:text-white' }}"
               id="nav-debit">
                <svg class="w-5 h-5 shrink-0 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v12M15 9.5a2.5 2.5 0 0 0-5 0c0 2.5 5 2.5 5 5a2.5 2.5 0 0 1-5 0"/>
                </svg>
                Debit
            </a>
        </div>

        {{-- Stock --}}
        @php $isSto = request()->routeIs('stock.*'); @endphp
        <div class="relative flex items-center">
            @if($isSto)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-[40px] bg-[#D4AF37] z-10"></span>
            @endif
            <a href="{{ route('stock.index') }}"
               class="w-[242px] ml-[14px] h-[40px] px-4 rounded-tl-[100px] rounded-bl-[100px] rounded-tr-[4px] rounded-br-[4px] flex items-center gap-3.5 text-sm font-semibold transition-all duration-200 {{ $isSto ? 'bg-[#D4AF37] text-white shadow-md' : 'text-white/90 hover:bg-white/10 hover:text-white' }}"
               id="nav-stock">
                <svg class="w-5 h-5 shrink-0 stroke-current fill-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
                stock
            </a>
        </div>

    </div>

</nav>

{{-- Mobile Sidebar Backdrop --}}
<div class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden transition-opacity" id="sidebar-backdrop"></div>
