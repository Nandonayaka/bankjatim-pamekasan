@extends('layouts.app')

@section('title', 'Debit')
@section('page_title', 'Debit')
@section('page_subtitle', 'Keuntungan penjualan')

@section('content')

{{-- Sub-header Action Bar --}}
<div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 mb-6 shadow-xs flex items-center justify-between flex-wrap gap-3 min-h-[64px]">
    <form method="GET" action="{{ route('debit.index') }}" class="flex items-center gap-2.5 flex-1 max-w-sm">
        <div class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="search" class="w-full bg-white border border-slate-300 rounded-full py-2 pl-10 pr-4 text-xs text-slate-800 outline-none focus:border-[#C8102E] focus:ring-2 focus:ring-[#C8102E]/15 transition-all placeholder:text-slate-400" placeholder="Search"
                   value="{{ $search }}">
        </div>
        @if($month)
            <input type="hidden" name="month" value="{{ $month }}">
        @endif
    </form>
    <div class="flex items-center gap-2.5">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-[10px] px-[15px] py-[10px] h-[37px] bg-white border border-[#C8102E] text-[#C8102E] hover:bg-rose-50 rounded-[10px] text-xs font-semibold transition-all">
            ← Kembali
        </a>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white border border-slate-200 rounded-[16px] p-6 shadow-xs">
    <h3 class="text-base font-bold text-slate-800 mb-4">Total bulanan</h3>

    <div class="overflow-x-auto border border-slate-200 rounded-[16px]">
        <table class="w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-[#C8102E] text-white h-[40px] rounded-t-[8px]">
                    <th class="w-[12%] h-[40px] px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle first:rounded-tl-[8px]">Tgl</th>
                    <th class="w-[28%] h-[40px] px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Nama barang</th>
                    <th class="w-[15%] h-[40px] px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Jumlah barang</th>
                    <th class="w-[15%] h-[40px] px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Harga kulaan</th>
                    <th class="w-[15%] h-[40px] px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Harga jual</th>
                    <th class="w-[15%] h-[40px] px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle last:rounded-tr-[8px]">Laba</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-[#4B5563]">
                @forelse($records as $i => $r)
                    @php $laba = $r->laba; @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors h-[52px]">
                        <td class="py-3 px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ \Carbon\Carbon::parse($r->transaction_date)->format('j / m / y') }}</td>
                        <td class="py-3 px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ strtolower($r->item_name) }}</td>
                        <td class="py-3 px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ number_format($r->quantity, 0, ',', '.') }} Lembar</td>
                        <td class="py-3 px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">RP . {{ number_format($r->harga_kulaan, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">RP . {{ number_format($r->harga_jual, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">
                            RP . {{ number_format($laba, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-[14px] font-medium text-slate-400">
                            Belum ada data keuntungan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($records->hasPages())
        <div class="flex items-center justify-center gap-1.5 mt-6 text-xs font-bold">
            @if($records->onFirstPage())
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">‹</span>
            @else
                <a href="{{ $records->previousPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">‹</a>
            @endif

            @foreach($records->getUrlRange(1, min(3, $records->lastPage())) as $page => $url)
                @if($page == $records->currentPage())
                    <span class="w-7 h-7 bg-[#C8102E] text-white rounded flex items-center justify-center shadow-xs">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $page }}</a>
                @endif
            @endforeach

            @if($records->lastPage() > 3)
                <span class="w-7 h-7 flex items-center justify-center text-slate-400">...</span>
                <a href="{{ $records->url($records->lastPage()) }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $records->lastPage() }}</a>
            @endif

            @if($records->hasMorePages())
                <a href="{{ $records->nextPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">›</a>
            @else
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">›</span>
            @endif
        </div>
    @endif
</div>

@endsection
