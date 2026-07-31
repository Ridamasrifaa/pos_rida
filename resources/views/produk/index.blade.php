@extends('layouts.app')

@section('title', 'Manajemen Produk - POS Rida')

@section('content')

<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4">

    <!-- Header Halaman & Tombol Tambah (Hanya untuk Admin) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Produk</h1>
                <p class="text-sm text-slate-500 mt-0.5">Daftar seluruh inventaris barang, stok, dan harga produk.</p>
            </div>
        </div>
        
        @if(Auth::user() && Auth::user()->role->name === 'admin')
        <div>
            <a href="{{ route('produk.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Produk Baru
            </a>
        </div>
        @endif
    </div>

    <!-- Alert / Modal Notifikasi Gagal Hapus (Hanya muncul jika ada session error dari Controller saat hapus) -->
    @if(session('error'))
        <div id="errorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 flex hidden">
            <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="text-center space-y-1">
                    <h3 class="font-bold text-lg text-slate-800">Tidak Dapat Menghapus Produk</h3>
                    <p class="text-sm text-slate-500">{{ session('error') }}</p>
                </div>
                <div class="pt-2">
                    <button type="button" onclick="closeErrorModal()" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('errorModal').classList.remove('hidden');
            });

            function closeErrorModal() {
                document.getElementById('errorModal').classList.add('hidden');
            }
        </script>
    @endif

    <!-- Tabel Daftar Produk -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Tabel Produk</h3>
                <span class="text-xs text-slate-400 font-medium">Total: <span id="total-produk">{{ $products->total() ?? 0 }}</span> Produk</span>
            </div>

            <!-- Form Pencarian (Realtime) -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari nama produk..." class="w-full sm:w-64 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto p-2">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th scope="col" class="py-3 px-4">No</th>
                        <th scope="col" class="py-3 px-4">Foto</th>
                        <th scope="col" class="py-3 px-4">Nama Produk</th>
                        <th scope="col" class="py-3 px-4">Jenis</th>
                        <th scope="col" class="py-3 px-4">Harga Beli</th>
                        <th scope="col" class="py-3 px-4">Harga Jual</th>
                        <th scope="col" class="py-3 px-4">Stok</th>
                        <th scope="col" class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="product-list" class="text-slate-600 divide-y divide-slate-50">
                    @forelse ($products as $index => $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($product->foto)
                                    @php
                                        $fotoPath = Str::startsWith($product->foto, ['http://', 'https://']) 
                                            ? $product->foto 
                                            : (Str::startsWith($product->foto, 'storage/') 
                                                ? asset($product->foto) 
                                                : (Str::startsWith($product->foto, '/') 
                                                    ? asset('storage' . $product->foto) 
                                                    : asset('storage/' . $product->foto)));
                                    @endphp
                                    <img src="{{ $fotoPath }}" alt="{{ $product->nama }}" class="w-10 h-10 object-cover rounded-xl border border-slate-100 shadow-sm">
                                @else
                                    <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-xs font-bold">No</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800">{{ $product->nama }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">
                                    {{ $product->jenis->nama_jenis ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                Rp {{ number_format($product->harga_beli ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                Rp {{ number_format($product->harga_jual ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $product->stok > 5 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $product->stok }} Pcs
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center space-x-2">
                                <a href="{{ route('produk.show', $product->id) }}" class="inline-block px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-semibold transition">
                                    Detail
                                </a>

                                <!-- Tombol Edit & Hapus hanya tampil jika user yang login adalah Admin -->
                                @if(Auth::user() && Auth::user()->role->name === 'admin')
                                    <a href="{{ route('produk.edit', $product->id) }}" class="inline-block px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold transition">
                                        Edit
                                    </a>

                                    <button type="button" onclick="openDeleteModal('{{ route('produk.destroy', $product->id) }}', '{{ $product->nama }}')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition inline-block">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 text-xs">Data produk tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="p-4 border-t border-slate-100 bg-white">
            {{ $products->links() }}
        </div>
    </div>

</div>

<!-- Modal Konfirmasi Hapus Produk (Hanya untuk Admin) -->
@if(Auth::user() && Auth::user()->role->name === 'admin')
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="text-center space-y-1">
            <h3 class="font-bold text-lg text-slate-800">Hapus Produk?</h3>
            <p class="text-sm text-slate-500">Apakah Anda yakin ingin menghapus produk <span id="productNameTarget" class="font-semibold text-slate-700"></span>?</p>
        </div>
        <div class="flex items-center gap-2 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                Batal
            </button>
            <form id="deleteFormTarget" method="POST" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Script Live Search & Modal Control -->
<script>
    function openDeleteModal(deleteUrl, productName) {
        document.getElementById('productNameTarget').innerText = '"' + productName + '"';
        document.getElementById('deleteFormTarget').action = deleteUrl;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('search-input').addEventListener('input', function() {
        let query = this.value;

        fetch(`{{ route('produk') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('product-list').innerHTML = data.html;
            document.getElementById('total-produk').innerText = data.total;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        })
        .catch(error => console.error('Error:', error));
    });
</script>

@endsection