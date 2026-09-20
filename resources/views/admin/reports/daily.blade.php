@extends('layouts.app')

@section('title', 'Rincian Harian - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fade-in">

    <!-- Header Halaman & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Rincian Harian: {{ $monthName }}</h1>
            <p class="text-sm text-slate-600 mt-0.5">Daftar transaksi dan rincian barang selama bulan ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Tombol Download Rekap -->
            <a href="{{ route('admin.reports.daily.download', ['year' => $year, 'month' => $month]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Download PDF
            </a>
            <a href="{{ route('admin.reports.monthly', ['year' => $year]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Ringkasan Singkat (Cards) -->
    @php
        $totalOmzetBulanIni = $dailyData->sum('total_omzet');
        $totalTransaksiBulanIni = $dailyData->sum('total_transaksi');
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- Card Total Omzet -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all duration-300 hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Omzet Bulan Ini</p>
                <h3 class="text-xl font-extrabold text-slate-800 mt-0.5">Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card Total Transaksi -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all duration-300 hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-xl font-extrabold text-slate-800 mt-0.5">{{ $totalTransaksiBulanIni }} Transaksi</h3>
            </div>
        </div>

    </div>

    <!-- Tabel Master-Detail Transaksi -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden space-y-4 p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="font-bold text-lg text-slate-800">Daftar Nota Transaksi</h3>
            <span class="text-xs font-semibold text-slate-600">Klik tombol "Detail" pada baris untuk melihat rincian barang</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr class="text-slate-700 text-xs uppercase tracking-wider border-b border-slate-200 font-bold">
                        <th class="bg-transparent py-3">ID / Waktu</th>
                        <th class="bg-transparent py-3">Jumlah Jenis Item</th>
                        <th class="bg-transparent py-3">Total Pembayaran</th>
                        <th class="bg-transparent py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @foreach($transactions as $trx)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                            <td class="py-4">
                                <span class="font-bold text-slate-800">#{{ $trx->id }}</span>
                                <div class="text-xs font-medium text-slate-600 mt-0.5">{{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i') }}</div>
                            </td>
                            <td class="py-4 text-slate-700 font-semibold">
                                {{ $trx->itemPenjualans->count() }} Item Barang
                            </td>
                            <td class="py-4 font-extrabold text-emerald-600">
                                Rp {{ number_format($trx->total_pembayaran, 0, ',', '.') }}
                            </td>
                            <td class="py-4 text-center">
                                <button type="button" onclick="toggleDetail({{ $trx->id }})" id="btn-{{ $trx->id }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition inline-flex items-center gap-1">
                                    <span id="txt-{{ $trx->id }}">Detail</span>
                                    <svg id="icon-{{ $trx->id }}" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </td>
                        </tr>

                        <!-- Baris Rincian Item (Hidden by Default) -->
                        <tr id="detail-{{ $trx->id }}" class="hidden bg-slate-50/80 border-b border-slate-100">
                            <td colspan="4" class="p-4">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Rincian Barang Nota #{{ $trx->id }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs text-slate-700">
                                            <thead>
                                                <tr class="border-b border-slate-200 text-slate-700 font-bold">
                                                    <th class="py-2 text-left">Nama Barang</th>
                                                    <th class="py-2 text-center">Jumlah</th>
                                                    <th class="py-2 text-right">Harga Satuan</th>
                                                    <th class="py-2 text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($trx->itemPenjualans as $detail)
                                                    <tr class="border-b border-slate-100 last:border-0 font-medium">
                                                        <td class="py-2 text-slate-800 font-semibold">{{ $detail->produk->nama ?? 'Produk' }}</td>
                                                        <td class="py-2 text-center">
                                                            <span class="px-2 py-0.5 text-slate-700 rounded-md font-bold">
                                                                {{ $detail->kuantitas ?? $detail->jumlah ?? 1 }}
                                                            </span>
                                                        </td>
                                                        <td class="py-2 text-right text-slate-700">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                                        <td class="py-2 text-right font-extrabold text-emerald-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($transactions->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-500 font-medium text-xs">Belum ada transaksi pada bulan ini.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Style Animasi -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<!-- Vanilla JS Toggle -->
<script>
    function toggleDetail(id) {
        const detailRow = document.getElementById('detail-' + id);
        const textSpan = document.getElementById('txt-' + id);
        const iconSvg = document.getElementById('icon-' + id);

        if (detailRow.classList.contains('hidden')) {
            detailRow.classList.remove('hidden');
            textSpan.innerText = 'Tutup';
            iconSvg.classList.add('rotate-180');
        } else {
            detailRow.classList.add('hidden');
            textSpan.innerText = 'Detail';
            iconSvg.classList.remove('rotate-180');
        }
    }
</script>
@endsection