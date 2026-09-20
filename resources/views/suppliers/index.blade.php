@extends('layouts.app')

@section('title', 'Manajemen Suplier - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl shadow-inner transition-transform duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.182l3-1.091m-15-2.273l3-1.091m0 0l2.25-1.091L14.25 4.5v4.909" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Suplier</h1>
                <p class="text-sm font-normal text-slate-600 mt-0.5">Kelola data tempat kulakan atau distributor barang toko.</p>
            </div>
        </div>
        
        <div>
            <a href="{{ route('suppliers.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Suplier Baru
            </a>
        </div>
    </div>

    <!-- Tabel Data Suplier -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Tabel Suplier</h3>
                <span class="text-xs font-semibold text-slate-500">Total: <span id="total-suppliers" class="text-slate-800">{{ $suppliers->total() ?? count($suppliers) }}</span> Suplier</span>
            </div>

            <!-- Form Pencarian (Realtime Live Search) -->
            <div class="flex items-center w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari Nama / No. Telp / Alamat..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto p-2 relative">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-700 font-semibold text-xs uppercase tracking-wider border-b border-slate-200 bg-white">
                        <th scope="col" class="py-4 px-4 w-16 text-center">No</th>
                        <th scope="col" class="py-4 px-4">Nama Suplier / Sales</th>
                        <th scope="col" class="py-4 px-4">Nomor Telepon</th>
                        <th scope="col" class="py-4 px-4">Alamat</th>
                        <th scope="col" class="py-4 px-4">Status</th>
                        <th scope="col" class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="suppliers-list" class="text-slate-700 font-normal divide-y divide-slate-100">
                    @include('suppliers.partials.table')
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="p-4 border-t border-slate-200 bg-white">
            {{ $suppliers->links() }}
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

<!-- Script Live Search AJAX & Pagination Handler -->
<script>
    function fetchSuppliers(url) {
        let query = document.getElementById('search-input').value;
        let targetUrl = url || "{{ route('suppliers.index') }}?search=" + encodeURIComponent(query);

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
            document.getElementById('suppliers-list').innerHTML = data.html;
            document.getElementById('total-suppliers').innerText = data.total;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        })
        .catch(error => console.error('Error:', error));
    }

    // Trigger saat mengetik di input pencarian
    document.getElementById('search-input').addEventListener('input', function() {
        fetchSuppliers();
    });

    // Trigger saat tombol pagination diklik tanpa reload halaman
    document.addEventListener('click', function(event) {
        let paginationLink = event.target.closest('#pagination-container a');
        if (paginationLink) {
            event.preventDefault();
            let url = paginationLink.href;
            fetchSuppliers(url);
        }
    });
</script>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md mx-4 rounded-3xl shadow-2xl p-6 transform scale-95 transition-transform duration-300">
        <div class="text-center space-y-4">
            <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto text-xl font-bold shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Suplier?</h3>
                <p id="deleteModalMessage" class="text-sm text-slate-500 mt-1">Apakah Anda yakin ingin menghapus suplier ini?</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Peringatan Gagal Hapus (Jika Terikat Produk) -->
@if(session('error'))
<div id="errorModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-md mx-4 rounded-3xl shadow-2xl p-6">
        <div class="text-center space-y-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto text-xl font-bold shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tidak Dapat Dihapus</h3>
                <p class="text-sm text-slate-500 mt-1">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="document.getElementById('errorModal').remove()" class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all">
                Mengerti
            </button>
        </div>
    </div>
</div>
@endif

<!-- JavaScript untuk Kontrol Modal -->
<script>
    function openDeleteModal(actionUrl, supplierName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const message = document.getElementById('deleteModalMessage');

        form.action = actionUrl;
        message.innerHTML = `Apakah Anda yakin ingin menghapus suplier <span class="font-semibold text-slate-800">"${supplierName}"</span>?`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div > div').classList.remove('scale-95');
            modal.querySelector('div > div').classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div > div').classList.remove('scale-100');
        modal.querySelector('div > div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection