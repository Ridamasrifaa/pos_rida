@extends('layouts.app')

@section('title', 'Manajemen Produk - POS Rida')

@section('content')

<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 animate-pageIn">

    <!-- Header Halaman & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl shadow-inner transition-transform duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Produk</h1>
                <p class="text-sm font-normal text-slate-600 mt-0.5">Daftar seluruh inventaris barang, stok, dan harga produk.</p>
            </div>
        </div>
        
        @if(Auth::user() && Auth::user()->role->name === 'admin')
        <div>
            <a href="{{ route('produk.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Produk Baru
            </a>
        </div>
        @endif
    </div>

    <!-- Tabel Daftar Produk -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Tabel Produk</h3>
                <span class="text-xs font-semibold text-slate-500">Total: <span id="total-produk" class="text-slate-800">{{ $products->total() ?? 0 }}</span> Produk</span>
            </div>

            <!-- Form Pencarian (Realtime) -->
            <div class="flex items-center w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari Produk..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto p-2">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-700 font-semibold text-xs uppercase tracking-wider border-b border-slate-200 bg-white">
                        <th scope="col" class="py-4 px-4 w-16 text-center">No</th>
                        <th scope="col" class="py-4 px-4">Foto</th>
                        <th scope="col" class="py-4 px-4">Nama Produk</th>
                        <th scope="col" class="py-4 px-4">Jenis</th>
                        <th scope="col" class="py-4 px-4">Dibuat Oleh</th>
                        <th scope="col" class="py-4 px-4">Harga Beli</th>
                        <th scope="col" class="py-4 px-4">Harga Jual</th>
                        <th scope="col" class="py-4 px-4">Stok</th>
                        <th scope="col" class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="product-list" class="text-slate-700 font-normal divide-y divide-slate-100">
                    @forelse ($products as $index => $product)
                        <tr class="transition-colors duration-150 hover:bg-slate-50/50">
                            <td class="py-4 px-4 text-center font-normal text-slate-700">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4 min-w-[100px]">
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
                                    <img src="{{ $fotoPath }}" alt="{{ $product->nama }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border border-slate-200 shadow-sm flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center text-xs font-bold border border-slate-200 flex-shrink-0">No</div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-normal text-slate-800">{{ $product->nama }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 rounded-xl text-xs font-semibold whitespace-nowrap">
                                    {{ $product->jenis->nama_jenis ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-normal text-slate-700">
                                    {{ $product->user->name ?? 'Admin' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal whitespace-nowrap">
                                Rp {{ number_format($product->harga_beli ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal whitespace-nowrap">
                                Rp {{ number_format($product->harga_jual ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded-xl text-xs font-semibold {{ $product->stok > 5 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $product->stok }} Pcs
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('produk.show', $product->id) }}" class="px-3.5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-full text-xs font-semibold shadow-xs transition active:scale-95">
                                        Detail
                                    </a>

                                    @if(Auth::user() && Auth::user()->role->name === 'admin')
                                        <a href="{{ route('produk.edit', $product->id) }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-full text-xs font-semibold shadow-xs transition active:scale-95">
                                            Edit
                                        </a>

                                        <button type="button" onclick="openDeleteModal('{{ route('produk.destroy', $product->id) }}', '{{ $product->nama }}')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-full text-xs font-semibold shadow-xs transition active:scale-95 cursor-pointer">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-500 font-normal text-sm">Data produk tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="p-4 border-t border-slate-200 bg-white">
            {{ $products->links() }}
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL ERROR KUSTOM -->
<!-- ========================================== -->
@if(session('error'))
    <div id="errorModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-900/70">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 space-y-5 text-center">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl mx-auto shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div class="space-y-2">
                <h3 class="font-bold text-lg text-slate-900">Tidak Dapat Menghapus Produk</h3>
                <p class="text-sm font-normal text-slate-600 leading-relaxed">Produk ini tidak dapat dihapus karena sudah tercatat dalam riwayat transaksi penjualan.</p>
            </div>
            <div>
                <button type="button" onclick="closeErrorModal()" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-sm font-semibold shadow-md shadow-amber-500/20 transition cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            if(modal) modal.remove();
        }
    </script>
@endif

<!-- ========================================== -->
<!-- MODAL KONFIRMASI HAPUS KUSTOM (CARD) -->
<!-- ========================================== -->
@if(Auth::user() && Auth::user()->role->name === 'admin')
<div id="deleteModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-900/70 hidden">
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 space-y-5 text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl mx-auto shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="space-y-2">
            <h3 class="font-bold text-lg text-slate-900">Hapus Produk?</h3>
            <p class="text-sm font-normal text-slate-600 leading-relaxed">Apakah Anda yakin ingin menghapus produk <span id="productNameTarget" class="font-semibold text-slate-800"></span>?</p>
        </div>
        <div class="flex items-center gap-2 pt-1">
            <button type="button" onclick="closeDeleteModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-semibold transition cursor-pointer">
                Batal
            </button>
            <form id="deleteFormTarget" method="POST" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-sm font-semibold shadow-md shadow-rose-600/20 transition cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    @keyframes pageFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-pageIn {
        animation: pageFadeIn 0.3s ease-out forwards;
    }
</style>

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

    function fetchProducts(url) {
        let query = document.getElementById('search-input').value;
        let targetUrl = url || "{{ route('produk') }}?search=" + encodeURIComponent(query);

        if (!targetUrl.includes('search=') && query) {
            let separator = targetUrl.includes('?') ? '&' : '?';
            targetUrl += `${separator}search=${encodeURIComponent(query)}`;
        }

        fetch(targetUrl, {
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
    }

    document.getElementById('search-input').addEventListener('input', function() {
        fetchProducts();
    });

    document.addEventListener('click', function(event) {
        let paginationLink = event.target.closest('#pagination-container a');
        if (paginationLink) {
            event.preventDefault();
            let url = paginationLink.href;
            fetchProducts(url);
        }
    });
</script>

@endsection