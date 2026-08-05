@extends('layouts.app')

@section('title', 'Detail Produk - POS Rida')

@section('content')

<div class="w-full max-w-3xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">
    <!-- Header Halaman -->
    <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Produk</h1>
        <p class="text-sm font-normal text-slate-600 mt-0.5">Informasi lengkap mengenai data produk di dalam sistem.</p>
    </div>

    <!-- Konten Detail Produk -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 p-6 space-y-5">
        
        <!-- Foto Produk -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Foto Produk</label>
            <div class="w-20 h-20 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-sm flex-shrink-0">
                @if ($product->foto)
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover">
                @else
                    <span class="text-slate-400 text-xs font-medium text-center p-1">Belum ada foto</span>
                @endif
            </div>
        </div>

        <!-- Nama Produk -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama Produk</label>
            <div class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800">
                {{ $product->nama }}
            </div>
        </div>

        <!-- Jenis Produk -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Jenis Produk</label>
            <div class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800">
                {{ $product->jenis->nama_jenis ?? '-' }}
            </div>
        </div>

        <!-- Harga Beli & Harga Jual -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga Beli (Rp)</label>
                <div class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800">
                    Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga Jual (Rp)</label>
                <div class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800">
                    Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Stok -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Stok Tersedia</label>
            <div class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800">
                {{ intval($product->stok) }} Pcs
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="pt-4 flex justify-end border-t border-slate-100">
            <a href="{{ route('produk') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                Kembali
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>

@endsection