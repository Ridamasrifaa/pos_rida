@extends('layouts.app')

@section('title', 'Edit Produk - POS Rida')

@section('content')

    <div class="w-full max-w-3xl mx-auto space-y-6 font-sans py-6 px-4">
        <div class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Edit Produk</h1>
                <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi data produk.</p>
            </div>
            <a href="{{ route('produk') }}"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                Kembali
            </a>
        </div>

        <!-- Alert Validasi Error -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl text-sm">
                <span class="font-bold block mb-1">Terjadi kesalahan pengisian form:</span>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <form action="{{ route('produk.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Preview & Input Foto -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Foto
                        Produk</label>
                    <div class="flex items-center gap-4 mb-3">
                        @if ($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}"
                                class="w-16 h-16 object-cover rounded-2xl border border-slate-200 shadow-sm">
                        @else
                            <div
                                class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-xs font-bold border border-slate-200">
                                No Photo</div>
                        @endif
                        <div class="text-xs text-slate-400">
                            Biarkan kosong jika tidak ingin mengubah foto produk.
                        </div>
                    </div>
                    <input type="file" name="foto"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100">
                </div>

                <!-- Input Jenis Produk -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Jenis Produk</label>
                    <select name="jenis_id" id="jenis_id" required class="w-full px-4 py-2.5 rounded-xl border @error('jenis_id') border-rose-500 @else border-slate-200 @enderror focus:outline-none focus:border-rose-500 text-sm bg-white">
                        <option value="" disabled>-- Pilih Jenis Produk --</option>
                        @foreach ($data_jenis as $jenis)
                            <option value="{{ $jenis->id }}" {{ old('jenis_id', $product->jenis_id) == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_id')
                        <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama
                        Produk</label>
                    <input type="text" name="nama" value="{{ old('nama', $product->nama) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-500 text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga
                            Beli</label>
                        <input type="number" name="harga_beli"
                            value="{{ old('harga_beli', intval($product->harga_beli)) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga
                            Jual</label>
                        <input type="number" name="harga_jual"
                            value="{{ old('harga_jual', intval($product->harga_jual)) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', intval($product->stok)) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-500 text-sm">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('produk') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection