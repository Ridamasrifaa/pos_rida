@extends('layouts.app')

@section('title', 'Tambah Produk Baru - POS Rida')

@section('content')



<div class="w-full max-w-3xl mx-auto space-y-6 font-sans py-6 px-4">
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Tambah Produk Baru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Masukkan data inventaris produk baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('produk') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Input Foto -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Foto Produk</label>
                <input type="file" name="foto" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100">
                @error('foto')
                    <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Nama -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Kopi Susu Aren" class="w-full px-4 py-2.5 rounded-xl border @error('nama') border-rose-500 @else border-slate-200 @enderror focus:outline-none focus:border-rose-500 text-sm">
                @error('nama')
                    <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Input Harga Beli -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" placeholder="Contoh: 10000" class="w-full px-4 py-2.5 rounded-xl border @error('harga_beli') border-rose-500 @else border-slate-200 @enderror focus:outline-none focus:border-rose-500 text-sm">
                    @error('harga_beli')
                        <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Input Harga Jual -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" placeholder="Contoh: 15000" class="w-full px-4 py-2.5 rounded-xl border @error('harga_jual') border-rose-500 @else border-slate-200 @enderror focus:outline-none focus:border-rose-500 text-sm">
                    @error('harga_jual')
                        <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Input Stok -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Stok Awal</label>
                <input type="number" name="stok" value="{{ old('stok') }}" placeholder="Contoh: 50" class="w-full px-4 py-2.5 rounded-xl border @error('stok') border-rose-500 @else border-slate-200 @enderror focus:outline-none focus:border-rose-500 text-sm">
                @error('stok')
                    <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('produk') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

@endsection