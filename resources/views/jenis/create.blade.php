@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <h1 class="text-xl font-bold text-slate-800 mb-6">Tambah Jenis Produk</h1>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
        <form action="{{ route('jenis.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nama_jenis" class="block text-xs font-bold text-slate-700 mb-2">Nama Jenis</label>
                <input type="text" name="nama_jenis" id="nama_jenis" value="{{ old('nama_jenis') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-rose-500">
                @error('nama_jenis')
                    <span class="text-[10px] text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('jenis.index') }}" class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold text-center transition">Batal</a>
                <button type="submit" class="w-1/2 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection