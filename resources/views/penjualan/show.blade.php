@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $penjualan->id . ' - TokoGO')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Transaksi #{{ $penjualan->id }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Informasi lengkap rincian barang dan pembayaran.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('penjualan.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-sm font-bold transition">Kembali</a>
            
            <!-- Tombol Cetak Struk (Tema Merah & Ikon SVG) -->
            <button onclick="window.print()" type="button" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-sm transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </button>
        </div>
    </div>

    <!-- AREA UTAMA YANG AKAN DICETAK (ID print-area) -->
    <div id="print-area" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-2xl text-sm">
            <div>
                <span class="block text-slate-400 text-xs font-semibold">Kasir</span>
                <span class="font-bold text-slate-700">{{ $penjualan->user->name ?? 'Unknown' }}</span>
            </div>
            <div>
                <span class="block text-slate-400 text-xs font-semibold">Metode</span>
                <span class="font-bold uppercase text-slate-700">{{ $penjualan->metode_pembayaran }}</span>
            </div>
            <div>
                <span class="block text-slate-400 text-xs font-semibold">Status</span>
                <span class="font-bold uppercase text-slate-700">{{ $penjualan->status }}</span>
            </div>
            <div>
                <span class="block text-slate-400 text-xs font-semibold">Waktu</span>
                <span class="font-bold text-slate-700">{{ $penjualan->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-lg text-slate-800 mb-3">Daftar Barang Dibeli</h3>
            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="table w-full">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase bg-slate-50/50">
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($penjualan->itemPenjualans as $item)
                        <tr>
                            <td>{{ $item->produk->nama ?? 'Produk Dihapus' }}</td>
                            <td>Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $item->kuantitas ?? 0 }}</td>
                            <td class="text-right font-semibold">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-slate-400">Tidak ada item barang dalam transaksi ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
            <span class="font-bold text-slate-600">Total Pembayaran:</span>
            <span class="text-xl font-extrabold text-rose-600">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<!-- CSS KHUSUS PRINT: Hanya bagian #print-area saja yang dicetak -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #print-area, #print-area * {
        visibility: visible;
    }
    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
        padding: 0;
    }
    /* Sembunyikan tombol kembali dan tombol cetak saat proses cetak berjalan */
    .btn, button {
        display: none !important;
    }
}
</style>
@endsection