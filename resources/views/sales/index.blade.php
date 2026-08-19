@extends('layouts.app')

@section('title', 'Selling Price')
@section('page_title', 'Selling Price')
@section('page_subtitle', 'Display harga barang siap jual')

@section('content')

{{-- Sub-header Action Bar --}}
<div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-3.5 mb-5 sm:mb-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 min-h-[64px]">
    <form method="GET" action="{{ route('sales.index') }}" class="flex items-center gap-2.5 w-full sm:w-auto flex-1 max-w-sm">
        <div class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="search" id="search-input"
                   class="w-full bg-white border border-slate-300 rounded-full py-2 pl-10 pr-4 text-xs text-slate-800 outline-none focus:border-[#C8102E] focus:ring-2 focus:ring-[#C8102E]/15 transition-all placeholder:text-slate-400"
                   placeholder="Cari nama barang..." value="{{ $search }}">
        </div>
    </form>
    <div class="flex items-center gap-2.5 w-full sm:w-auto">
        <button id="btn-open-modal"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-[10px] px-[15px] py-[10px] h-[37px] bg-[#C8102E] hover:bg-[#a30a25] text-white rounded-[10px] text-xs font-semibold transition-all shadow-xs">
            + Tambah barang
        </button>
        <a href="{{ route('dashboard') }}"
           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-[10px] px-[15px] py-[10px] h-[37px] bg-white border border-[#C8102E] text-[#C8102E] hover:bg-rose-50 rounded-[10px] text-xs font-semibold transition-all">
            ← Kembali
        </a>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white border border-slate-200 rounded-[16px] p-4 sm:p-6 shadow-xs">
    <h3 class="text-base font-bold text-slate-800 mb-4">Total bulanan</h3>

    <div class="overflow-x-auto border border-slate-200 rounded-[16px] -mx-1 sm:mx-0">
        <table class="min-w-[560px] w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-[#C8102E] text-white h-[40px] rounded-t-[8px]">
                    <th class="w-[35%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle first:rounded-tl-[8px]">Nama barang</th>
                    <th class="w-[25%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Harga asli</th>
                    <th class="w-[25%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Harga siap jual</th>
                    <th class="w-[15%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle text-right last:rounded-tr-[8px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-[#4B5563]">
                @forelse($displays as $d)
                    <tr class="hover:bg-slate-50/80 transition-colors h-[52px]">
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ strtolower($d->item?->item_name ?? '-') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">
                            @if($d->harga_asli)
                                RP . {{ number_format($d->harga_asli, 0, ',', '.') }}
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">RP . {{ number_format($d->harga_siap_jual, 0, ',', '.') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-right border-b border-slate-200 align-middle">
                            <form method="POST" action="{{ route('sales.destroy', $d->id) }}"
                                  onsubmit="return confirm('Hapus data display ini?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-md transition-colors" title="Hapus">
                                    <svg class="w-3.5 h-3.5 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                        <path d="M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-[14px] font-medium text-slate-400">
                            Belum ada barang yang ditambahkan ke display
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($displays->hasPages())
        <div class="flex items-center justify-center gap-1.5 mt-6 text-xs font-bold flex-wrap">
            @if($displays->onFirstPage())
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">‹</span>
            @else
                <a href="{{ $displays->previousPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">‹</a>
            @endif

            @foreach($displays->getUrlRange(1, min(3, $displays->lastPage())) as $page => $url)
                @if($page == $displays->currentPage())
                    <span class="w-7 h-7 bg-[#C8102E] text-white rounded flex items-center justify-center shadow-xs">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $page }}</a>
                @endif
            @endforeach

            @if($displays->lastPage() > 3)
                <span class="w-7 h-7 flex items-center justify-center text-slate-400">...</span>
                <a href="{{ $displays->url($displays->lastPage()) }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $displays->lastPage() }}</a>
            @endif

            @if($displays->hasMorePages())
                <a href="{{ $displays->nextPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">›</a>
            @else
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">›</span>
            @endif
        </div>
    @endif
</div>

{{-- Modal: Tambah Barang Display --}}
<div class="modal-overlay fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-3.5 sm:p-5" id="modal-overlay">
    <div class="modal-card bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl p-5 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <button id="btn-close-modal"
                class="absolute top-4 sm:top-5 right-4 sm:right-5 text-slate-400 hover:text-slate-700 transition-colors"
                aria-label="Tutup">
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-5 sm:mb-6">Tambah barang display</h3>

        <form method="POST" action="{{ route('sales.store') }}" id="display-form">
            @csrf

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5" for="item_id">Nama barang</label>
                <select name="item_id" id="item_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E]">
                    <option value="">-- Pilih barang --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="harga_asli">
                        Harga asli <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold pointer-events-none">RP</span>
                        <input type="number" name="harga_asli" id="harga_asli"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E] placeholder:text-slate-400"
                               min="0" step="1" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="harga_siap_jual">
                        Harga siap jual <span class="text-slate-400 font-normal">(per pcs)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold pointer-events-none">RP</span>
                        <input type="number" name="harga_siap_jual" id="harga_siap_jual" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E] placeholder:text-slate-400"
                               min="0" step="1" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 h-[37px] bg-[#C8102E] hover:bg-[#a30a25] text-white rounded-[10px] text-xs font-bold shadow-md transition-all">
                    + Tambah
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const overlay  = document.getElementById('modal-overlay');
    const openBtn  = document.getElementById('btn-open-modal');
    const closeBtn = document.getElementById('btn-close-modal');

    function openModal()  { overlay.classList.add('open'); }
    function closeModal() { overlay.classList.remove('open'); }

    if (openBtn)  openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (overlay)  overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });

    @if($errors->any() || session('error')) openModal(); @endif
</script>
@endpush
