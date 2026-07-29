@extends('layouts.app')

@section('title', 'Detail Produk - POS Rida')

@section('content')



<div class="w-full max-w-4xl mx-auto space-y-6 font-sans py-6 px-4">
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Detail Produk</h1>
            <p class="text-sm text-slate-500 mt-0.5">Informasi lengkap mengenai data produk.</p>
        </div>
        <a href="{{ route('produk') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Nama Produk</span>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $product->nama }}</p>
            </div>
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Stok Tersedia</span>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $product->stok }} Pcs</p>
            </div>
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Harga Beli</span>
                <p class="text-lg font-medium text-slate-700 mt-1">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Harga Jual</span>
                <p class="text-lg font-medium text-emerald-600 mt-1">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex gap-3">
                <a href="{{ route('produk.edit', $product->id) }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition">
                Edit Produk Ini
            </a>
        </div>
    </div>
</div>

@endsection