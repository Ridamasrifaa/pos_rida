@extends('layouts.app')

@section('title', 'Edit Produk - POS Rida')

@section('content')

<div class="w-full max-w-3xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">
    <!-- Header Halaman (Tombol Kembali di atas sudah dihapus) -->
    <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Produk</h1>
        <p class="text-sm font-normal text-slate-600 mt-0.5">Perbarui informasi data produk ke dalam sistem.</p>
    </div>

    <!-- Alert Validasi Error -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl text-sm shadow-sm">
            <span class="font-bold block mb-1">Terjadi kesalahan pengisian form:</span>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-md border border-slate-200 p-6">
        <form action="{{ route('produk.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Custom Preview & Input Foto -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Foto Produk</label>
                
                <div class="flex items-center gap-4">
                    <!-- Kotak Preview Foto -->
                    <div id="preview-container" class="w-20 h-20 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0 text-slate-400 text-xs font-medium text-center p-2 shadow-sm">
                        @if ($product->foto)
                            <img id="image-preview" src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover">
                            <span id="preview-text" class="hidden">Belum ada foto</span>
                        @else
                            <span id="preview-text">Belum ada foto</span>
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>

                    <!-- Tombol Upload Custom -->
                    <div class="flex-grow">
                        <label for="foto-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-sm font-semibold transition border border-rose-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Ganti Foto Produk
                        </label>
                        <input type="file" name="foto" id="foto-input" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <p class="text-xs text-slate-400 mt-1.5">Biarkan kosong jika tidak ingin mengubah foto (Maks. 2MB)</p>
                    </div>
                </div>

                @error('foto')
                    <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Jenis Produk (Dropdown Style Dipermak Modern) -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Jenis Produk</label>
                <div class="relative">
                    <select name="jenis_id" id="jenis_id" required class="w-full px-4 py-3 rounded-xl border @error('jenis_id') border-rose-500 @else border-slate-200 @enderror bg-slate-50/50 focus:bg-white focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 text-sm font-medium text-slate-800 appearance-none transition cursor-pointer pr-10 shadow-sm">
                        <option value="" disabled class="text-slate-400">Pilih jenis produk...</option>
                        @foreach ($data_jenis as $jenis)
                            <option value="{{ $jenis->id }}" {{ old('jenis_id', $product->jenis_id) == $jenis->id ? 'selected' : '' }} class="py-2 text-slate-800 bg-white">
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Custom Chevron Icon -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
                @error('jenis_id')
                    <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Nama Produk -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama', $product->nama) }}" required placeholder="Contoh: Kopi Susu Aren" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 text-sm transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Input Harga Beli -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" value="{{ old('harga_beli', intval($product->harga_beli)) }}" required placeholder="Contoh: 10000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 text-sm transition">
                </div>
                <!-- Input Harga Jual -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" value="{{ old('harga_jual', intval($product->harga_jual)) }}" required placeholder="Contoh: 15000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 text-sm transition">
                </div>
            </div>

            <!-- Input Stok -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', intval($product->stok)) }}" required placeholder="Contoh: 50" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 text-sm transition">
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('produk') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Preview Gambar -->
<script>
    function previewImage(event) {
        let reader = new FileReader();
        let imagePreview = document.getElementById('image-preview');
        let previewText = document.getElementById('preview-text');

        reader.onload = function() {
            imagePreview.src = reader.result;
            imagePreview.classList.remove('hidden');
            if (previewText) {
                previewText.classList.add('hidden');
            }
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

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