@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $penjualan->id . ' - TokoGO')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- TAMPILAN NORMAL DI WEB -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Transaksi #{{ $penjualan->id }}</h1>
            <p class="text-sm text-slate-600 mt-0.5">Informasi lengkap rincian barang dan pembayaran.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('penjualan.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition">Kembali</a>
            
            <button onclick="window.print()" type="button" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-sm transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </button>
        </div>
    </div>

    <!-- AREA UTAMA DI WEB -->
    <div id="print-area" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6 print:hidden">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-2xl text-sm">
            <div>
                <span class="block text-slate-500 text-xs font-semibold">Kasir</span>
                <span class="font-bold text-slate-800">{{ $penjualan->user->name ?? 'Unknown' }}</span>
            </div>
            <div>
                <span class="block text-slate-500 text-xs font-semibold">Metode</span>
                <span class="font-bold uppercase text-slate-800">{{ $penjualan->metode_pembayaran }}</span>
            </div>
            <div>
                <span class="block text-slate-500 text-xs font-semibold">Status</span>
                <span class="font-bold uppercase text-slate-800">{{ $penjualan->status }}</span>
            </div>
            <div>
                <span class="block text-slate-500 text-xs font-semibold">Waktu</span>
                <span class="font-bold text-slate-800">{{ $penjualan->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- KONDISIONAL 1: JIKA QRIS, MUNCULKAN BARCODE -->
        @if(strtolower($penjualan->metode_pembayaran) === 'qris')
        <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-100 flex flex-col items-center justify-center space-y-3">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">BARCODE TRANSAKSI</span>
            <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm inline-block">
                <!-- Gunakan asset gambar qris kamu, atau placeholder qris jika belum ada file khusus -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=TOKOHOGO-TRX-{{ $penjualan->id }}" alt="QRIS Barcode" class="w-36 h-36 object-contain mx-auto">
            </div>
            <span class="text-xs text-slate-400">Scan QRIS di atas untuk melakukan pembayaran</span>
        </div>
        @endif

        <!-- KONDISIONAL 2: JIKA TRANSFER, MUNCULKAN NOMOR REKENING BANK -->
        @if(strtolower($penjualan->metode_pembayaran) === 'transfer')
        <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-100 flex flex-col items-center justify-center space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">SILAKAN TRANSFER PEMBAYARAN KE REKENING BERIKUT:</span>
            <div class="text-xl font-black text-rose-600 tracking-wide bg-white px-8 py-4 rounded-2xl border border-slate-200 shadow-sm">
                BCA: 123-456-7890 <span class="text-slate-700 font-bold block sm:inline text-base">(a.n. TokoGO)</span>
            </div>
            <span class="text-xs text-slate-400">Mohon transfer sesuai dengan total nominal pembayaran di bawah</span>
        </div>
        @endif

        <div>
            <h3 class="font-bold text-lg text-slate-800 mb-3">Daftar Barang Dibeli</h3>
            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-700 font-bold text-xs uppercase bg-slate-100">
                            <th class="p-3.5">Nama Produk</th>
                            <th class="p-3.5">Harga</th>
                            <th class="p-3.5">Qty</th>
                            <th class="p-3.5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @php
                            $subtotalKotor = 0;
                        @endphp
                        @forelse($penjualan->itemPenjualans as $item)
                        @php
                            $subtotalKotor += $item->subtotal ?? 0;
                        @endphp
                        <tr class="text-slate-800 font-medium">
                            <td class="p-3.5">{{ $item->produk->nama ?? 'Produk Dihapus' }}</td>
                            <td class="p-3.5">Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                            <td class="p-3.5">{{ $item->kuantitas ?? 0 }}</td>
                            <td class="p-3.5 text-right font-bold text-slate-900">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-slate-500">Tidak ada item barang dalam transaksi ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @php
            $isDiskon = $subtotalKotor >= 1000000;
            $besarDiskon = $isDiskon ? $subtotalKotor * 0.10 : 0;
        @endphp

        <div class="pt-4 border-t border-slate-200 space-y-2.5">
            <div class="flex justify-between items-center text-sm">
                <span class="font-semibold text-slate-600">Subtotal Barang:</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($subtotalKotor, 0, ',', '.') }}</span>
            </div>

            @if($isDiskon)
            <div class="flex justify-between items-center text-sm text-emerald-700 font-semibold bg-emerald-50 px-3 py-2 rounded-xl">
                <span>Diskon Belanja >= 1 Juta (10%):</span>
                <span>- Rp {{ number_format($besarDiskon, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="flex justify-between items-center text-sm">
                <span class="font-semibold text-slate-600">Total Pembayaran:</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</span>
            </div>

            @if(strtolower($penjualan->metode_pembayaran) === 'cash')
            <div class="flex justify-between items-center text-sm">
                <span class="font-semibold text-slate-600">Uang Diberikan (Tunai):</span>
                <span class="font-bold text-slate-800">
                    {{ $penjualan->uang_bayar ? 'Rp ' . number_format($penjualan->uang_bayar, 0, ',', '.') : 'Tidak tercatat / Pas' }}
                </span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="font-semibold text-slate-600">Kembalian:</span>
                <span class="font-bold text-emerald-600">
                    Rp {{ number_format(max(0, ($penjualan->uang_bayar ?? $penjualan->total_pembayaran) - $penjualan->total_pembayaran), 0, ',', '.') }}
                </span>
            </div>
            @endif

            <div class="flex justify-between items-center pt-3 border-t border-dashed border-slate-300">
                <span class="font-bold text-slate-700 text-base">Status Akhir:</span>
                <span class="text-xl font-extrabold text-rose-600">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- FORMAT STRUK KASIR PROFESIONAL (THERMAL)   -->
    <!-- ========================================== -->
    <div id="thermal-receipt" class="hidden">
        <div class="store-name">TOKO GO</div>
        <div class="store-address">Jl. Raya Tasikmalaya No. 123<br>Telp: 0812-3456-7890</div>
        
        <div class="trx-details">
            <div>No. Nota : #{{ $penjualan->id }}</div>
            <div>Waktu    : {{ $penjualan->created_at->format('d/m/Y H:i') }}</div>
            <div>Kasir    : {{ $penjualan->user->name ?? 'Admin' }}</div>
            <div>Metode   : <span style="text-transform: uppercase;">{{ $penjualan->metode_pembayaran }}</span></div>
        </div>

        <div class="dashed-line">-------------------------------------</div>

        <!-- INFO TAMBAHAN DI STRUK JIKA TRANSFER -->
        @if(strtolower($penjualan->metode_pembayaran) === 'transfer')
        <div style="text-align: center; font-size: 10px; margin-bottom: 4px;">
            <b>INFO TRANSFER REKENING:</b><br>
            BCA: 123-456-7890<br>
            (a.n. TokoGO)
        </div>
        <div class="dashed-line">-------------------------------------</div>
        @endif

        <table class="item-table">
            @foreach($penjualan->itemPenjualans as $item)
            <tr>
                <td colspan="2" class="item-name">{{ $item->produk->nama ?? 'Produk' }}</td>
            </tr>
            <tr>
                <td class="item-calc">
                    {{ $item->kuantitas }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}
                </td>
                <td class="item-subtotal">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </table>

        <div class="dashed-line">-------------------------------------</div>

        <table class="summary-table">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp {{ number_format($subtotalKotor, 0, ',', '.') }}</td>
            </tr>
            @if($isDiskon)
            <tr>
                <td>Diskon (10%)</td>
                <td class="text-right">-Rp {{ number_format($besarDiskon, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td class="bold">TOTAL</td>
                <td class="text-right bold">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</td>
            </tr>
            @if(strtolower($penjualan->metode_pembayaran) === 'cash')
            <tr>
                <td>Tunai</td>
                <td class="text-right">Rp {{ number_format($penjualan->uang_bayar ?? $penjualan->total_pembayaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">Rp {{ number_format(max(0, ($penjualan->uang_bayar ?? $penjualan->total_pembayaran) - $penjualan->total_pembayaran), 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <div class="dashed-line">-------------------------------------</div>

        <div class="footer-msg">
            TERIMA KASIH TELAH BERBELANJA<br>
            BARANG YANG SUDAH DIBELI TIDAK DAPAT<br>
            DITUKAR / DIKEMBALIKAN<br><br>
            === LAYANAN KONSUMEN TOKOGO ===
        </div>
    </div>
</div>

<!-- STYLING CSS KHUSUS CETAK STRUK KASIR -->
<style>
@media print {
    @page {
        margin: 0;
        size: 58mm auto;
    }
    body * {
        visibility: hidden;
    }
    #thermal-receipt, #thermal-receipt * {
        visibility: visible;
    }
    #thermal-receipt {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 54mm;
        font-family: 'Courier New', Courier, monospace;
        font-size: 11px;
        line-height: 1.2;
        color: #000;
        background: #fff;
        padding: 2mm;
    }
    .store-name {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 2px;
    }
    .store-address {
        text-align: center;
        font-size: 9px;
        margin-bottom: 6px;
    }
    .trx-details {
        font-size: 10px;
        margin-bottom: 4px;
    }
    .dashed-line {
        text-align: center;
        font-size: 10px;
        letter-spacing: -1px;
        margin: 3px 0;
    }
    .item-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    .item-name {
        font-weight: bold;
        padding-top: 2px;
    }
    .item-calc {
        color: #333;
    }
    .item-subtotal {
        text-align: right;
        font-weight: bold;
    }
    .summary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        margin-top: 2px;
    }
    .summary-table td {
        padding: 1px 0;
    }
    .bold {
        font-weight: bold;
        font-size: 11px;
    }
    .text-right {
        text-align: right;
    }
    .footer-msg {
        text-align: center;
        font-size: 9px;
        margin-top: 6px;
    }
}
</style>
@endsection