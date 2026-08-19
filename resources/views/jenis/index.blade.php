@extends('layouts.app')

@section('title', 'Manajemen Jenis Produk - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl shadow-inner transition-transform duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Jenis Produk</h1>
                <p class="text-sm font-normal text-slate-600 mt-0.5">Daftar seluruh kategori atau jenis produk barang toko.</p>
            </div>
        </div>
        
        <div>
            <a href="{{ route('jenis.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Jenis Baru
            </a>
        </div>
    </div>

    <!-- Tabel Data Jenis Produk -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Tabel Jenis Produk</h3>
                <span class="text-xs font-semibold text-slate-500">Total: <span id="total-jenis" class="text-slate-800">{{ $jenis->total() ?? count($jenis) }}</span> Jenis</span>
            </div>

            <!-- Form Pencarian (Realtime Live Search) -->
            <div class="flex items-center w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari Jenis Produk..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto p-2 relative">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-700 font-semibold text-xs uppercase tracking-wider border-b border-slate-200 bg-white">
                        <th scope="col" class="py-4 px-4 w-16 text-center">No</th>
                        <th scope="col" class="py-4 px-4">Admin / Kasir</th>
                        <th scope="col" class="py-4 px-4">Nama Jenis</th>
                        <th scope="col" class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="jenis-list" class="text-slate-700 font-normal divide-y divide-slate-100">
                    @include('jenis.partials.table')
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="p-4 border-t border-slate-200 bg-white">
            {{ $jenis->links() }}
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-5 transform transition-all">
        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-lg text-slate-800 tracking-tight">Hapus Jenis Produk?</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Data yang dihapus akan hilang secara permanen.</p>
        </div>
        
        <div class="grid grid-cols-2 gap-3 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition active:scale-95">
                Batal
            </button>
            <button type="button" id="confirmDeleteBtn" class="py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-xs shadow-sm transition active:scale-95">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- Modal Notifikasi Error -->
@if(session('error'))
<div id="errorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-5 transform transition-all">
        <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-lg text-slate-800 tracking-tight">Tidak Dapat Dihapus</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ session('error') }}</p>
        </div>
        
        <div class="pt-2">
            <button type="button" onclick="document.getElementById('errorModal').classList.add('hidden')" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-xs shadow-sm transition active:scale-95">
                Mengerti
            </button>
        </div>
    </div>
</div>
@endif

<!-- Script Live Search AJAX & Modal Control -->
<script>
    let activeFormId = null;

    function openDeleteModal(formId) {
        activeFormId = formId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        activeFormId = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (activeFormId) {
            document.getElementById(activeFormId).submit();
        }
    });

    // Fungsi Live Search AJAX
    function fetchJenis(url) {
        let query = document.getElementById('search-input').value;
        let targetUrl = url || "{{ route('jenis.index') }}?search=" + encodeURIComponent(query);

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
            document.getElementById('jenis-list').innerHTML = data.html;
            document.getElementById('total-jenis').innerText = data.total;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        })
        .catch(error => console.error('Error:', error));
    }

    // Trigger saat mengetik di input pencarian
    document.getElementById('search-input').addEventListener('input', function() {
        fetchJenis();
    });

    // Trigger saat tombol pagination diklik tanpa reload halaman
    document.addEventListener('click', function(event) {
        let paginationLink = event.target.closest('#pagination-container a');
        if (paginationLink) {
            event.preventDefault();
            let url = paginationLink.href;
            fetchJenis(url);
        }
    });
</script>
@endsection