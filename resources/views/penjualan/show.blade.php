@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $penjualan->id . ' - POS Rida')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Transaksi #{{ $penjualan->id }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Informasi lengkap rincian barang dan pembayaran.</p>
        </div>
        <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-ghost text-slate-600 rounded-xl">Kembali</a>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">
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
            <!-- Ubah dari $item->harga menjadi $item->harga_satuan -->
            <td>Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
            <!-- Ubah dari $item->qty menjadi $item->kuantitas -->
            <td>{{ $item->kuantitas ?? 0 }}</td>
            <!-- Gunakan harga_satuan dan kuantitas untuk subtotal -->
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
@endsection