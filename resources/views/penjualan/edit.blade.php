@extends('layouts.app')

@section('title', 'Edit Transaksi #' . $penjualan->id . ' - POS Rida')

@section('content')
<!-- Animasi Fade In & Slide Up Saat Halaman Pertama Kali Dibuka -->
<div x-data="editPosApp()" 
     class="space-y-6 relative opacity-0 translate-y-4 transition-all duration-700 ease-out"
     x-init="$el.classList.remove('opacity-0', 'translate-y-4')">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Transaksi #{{ $penjualan->id }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ubah item barang, metode pembayaran, atau status transaksi.</p>
        </div>
    </div>

    <!-- Layout 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- KOLOM KIRI: Daftar Produk Asli dari Database -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="font-bold text-lg text-slate-800">Daftar Produk</h3>
            
            <input type="text" x-model="search" placeholder="Cari produk..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500 transition">

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="flex items-center justify-between p-3 border border-slate-100 rounded-2xl hover:bg-slate-50/50 transition duration-300 transform hover:-translate-y-0.5"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        
                        <div class="flex items-center gap-3.5">
                            <!-- Gambar Produk (Menggunakan kolom 'foto') -->
                            <img :src="product.foto ? '{{ asset('storage') }}/' + product.foto : 'https://placehold.co/100x100?text=No+Image'" 
                                 alt="Foto Produk" 
                                 class="w-16 h-16 object-cover rounded-2xl border border-slate-100 shadow-sm flex-shrink-0 bg-slate-50">
                            
                            <div>
                                <div class="font-bold text-slate-800 text-sm" x-text="product.nama"></div>
                                <div class="text-xs text-slate-400 mt-0.5" x-text="'Stok: ' + product.stok"></div>
                                <div class="text-xs text-rose-600 font-semibold mt-1" x-text="'Rp ' + Number(product.harga_jual).toLocaleString('id-ID')"></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="number" x-model.number="product.tempQty" min="1" class="w-16 px-2 py-1.5 text-center bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500">
                            <button @click="addToCart(product)" type="button" class="w-9 h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition transform active:scale-90 flex items-center justify-center shadow-sm shadow-blue-200">
                                +
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- KOLOM KANAN: Keranjang / Item Barang & Form Update -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between space-y-6">
            <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST" @submit.prevent="confirmSave" class="flex flex-col justify-between h-full space-y-6" id="form-update-penjualan">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <h3 class="font-bold text-lg text-slate-800">Item Transaksi</h3>

                    <div class="overflow-x-auto border border-slate-100 rounded-2xl max-h-[280px] overflow-y-auto">
                        <table class="table w-full text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400">
                                    <th class="py-3 px-3">Produk</th>
                                    <th class="py-3 px-2">Harga</th>
                                    <th class="py-3 px-2">Qty</th>
                                    <th class="py-3 px-2">Subtotal</th>
                                    <th class="py-3 px-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in cart" :key="index">
                                    <tr class="border-b border-slate-50"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100">
                                        <td class="py-3 px-3 font-medium text-slate-700" x-text="item.nama"></td>
                                        <td class="py-3 px-2" x-text="'Rp ' + Number(item.harga_jual).toLocaleString('id-ID')"></td>
                                        <td class="py-3 px-2">
                                            <input type="number" x-model.number="item.qty" @change="updateQty(index)" min="1" class="w-12 px-1 py-1 text-center border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-rose-500">
                                        </td>
                                        <td class="py-3 px-2 font-semibold text-slate-800" x-text="'Rp ' + Number(item.harga_jual * item.qty).toLocaleString('id-ID')"></td>
                                        <td class="py-3 px-2">
                                            <button @click="removeFromCart(index)" type="button" class="px-2 py-1 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg text-[10px] font-bold transition">Hapus</button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="cart.length === 0">
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada item dipilih</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bagian Pembayaran & Pengaturan Status -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div>
                        <div class="text-xs text-slate-400 font-medium">Total Pembayaran</div>
                        <div class="text-2xl font-extrabold text-slate-800 mb-3" x-text="'Rp ' + totalBayar.toLocaleString('id-ID')"></div>
                        
                        <!-- Pilihan Metode Pembayaran Model Tombol Kartu Modern -->
                        <div class="space-y-2 mb-3">
                            <label class="block text-xs font-semibold text-slate-600">Metode Pembayaran <span class="text-rose-500">*</span></label>
                            
                            <input type="hidden" name="metode_pembayaran" x-model="metodePembayaran" required>

                            <div class="grid grid-cols-3 gap-2">
                                <!-- Opsi Cash -->
                                <button type="button" 
                                        @click="metodePembayaran = 'cash'"
                                        :class="metodePembayaran === 'cash' ? 'border-rose-500 bg-rose-50/50 text-rose-600 shadow-sm' : 'border-slate-200 bg-slate-50/50 text-slate-600 hover:bg-slate-100'"
                                        class="py-2.5 px-3 border rounded-xl text-xs font-bold transition flex flex-col items-center justify-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Cash
                                </button>

                                <!-- Opsi QRIS -->
                                <button type="button" 
                                        @click="metodePembayaran = 'qris'"
                                        :class="metodePembayaran === 'qris' ? 'border-rose-500 bg-rose-50/50 text-rose-600 shadow-sm' : 'border-slate-200 bg-slate-50/50 text-slate-600 hover:bg-slate-100'"
                                        class="py-2.5 px-3 border rounded-xl text-xs font-bold transition flex flex-col items-center justify-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    QRIS
                                </button>

                                <!-- Opsi Transfer -->
                                <button type="button" 
                                        @click="metodePembayaran = 'transfer'"
                                        :class="metodePembayaran === 'transfer' ? 'border-rose-500 bg-rose-50/50 text-rose-600 shadow-sm' : 'border-slate-200 bg-slate-50/50 text-slate-600 hover:bg-slate-100'"
                                        class="py-2.5 px-3 border rounded-xl text-xs font-bold transition flex flex-col items-center justify-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                    Transfer
                                </button>
                            </div>
                        </div>

                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Transaksi <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="statusTransaksi" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-rose-500 transition">
                            <option value="completed">COMPLETED</option>
                            <option value="open">OPEN</option>
                        </select>
                    </div>

                    <!-- Input tersembunyi untuk dikirim ke Controller -->
                    <input type="hidden" name="total_pembayaran" x-model="totalBayar">
                    <input type="hidden" name="items" x-model="JSON.stringify(cart)">

                    <!-- Tombol Aksi -->
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="showSaveModal = true" :disabled="cart.length === 0 || !metodePembayaran" class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-100 disabled:text-slate-400 text-white rounded-2xl font-bold text-sm shadow-sm shadow-amber-100 transition active:scale-95">
                                Simpan Perubahan
                            </button>

                            @if(strtolower($penjualan->status) === 'open')
                                <button type="button" @click="showDeleteModal = true" class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-sm shadow-rose-100 transition active:scale-95">
                                    Hapus Transaksi
                                </button>
                            @else
                                <a href="{{ route('penjualan.index') }}" class="block text-center w-full py-3.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl font-bold text-sm transition active:scale-95">
                                    Batalkan
                                </a>
                            @endif
                        </div>

                        @if(strtolower($penjualan->status) === 'open')
                            <a href="{{ route('penjualan.index') }}" class="block text-center w-full py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl font-bold text-sm transition active:scale-95">
                                Batalkan Transaksi
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Card Modal Konfirmasi Simpan Perubahan -->
            <div x-show="showSaveModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 p-4" x-transition.opacity>
                <div @click.outside="showSaveModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-slate-800">Simpan Perubahan?</h4>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Pastikan data item dan nominal pembayaran sudah benar sebelum menyimpan.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" @click="showSaveModal = false" class="py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition active:scale-95">
                            Batal
                        </button>
                        <button type="button" @click="executeSave" :disabled="isSaving" class="w-full py-3 bg-amber-500 hover:bg-amber-600 disabled:bg-amber-300 text-white rounded-2xl font-bold text-xs shadow-sm transition active:scale-95 flex items-center justify-center gap-2">
                            <span x-show="isSaving" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="isSaving ? 'Menyimpan...' : 'Ya, Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Modal Konfirmasi Hapus Transaksi -->
            @if(strtolower($penjualan->status) === 'open')
                <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 p-4" x-transition.opacity>
                    <div @click.outside="showDeleteModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-slate-800">Hapus Transaksi Ini?</h4>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Tindakan ini tidak dapat dibatalkan dan data transaksi akan dihapus permanen dari sistem.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <button type="button" @click="showDeleteModal = false" class="py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition active:scale-95">
                                Batal
                            </button>
                            <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" @submit="isDeleting = true">
                                @csrf
                                @method('DELETE')
                                <button type="submit" :disabled="isDeleting" class="w-full py-3 bg-rose-600 hover:bg-rose-700 disabled:bg-rose-400 text-white rounded-2xl font-bold text-xs shadow-sm transition active:scale-95 flex items-center justify-center gap-2">
                                    <span x-show="isDeleting" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                    <span x-text="isDeleting ? 'Menghapus...' : 'Ya, Hapus'"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- Script Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function editPosApp() {
        return {
            search: '',
            isSaving: false,
            isDeleting: false,
            showSaveModal: false,
            showDeleteModal: false,
            products: @json($products).map(p => ({
                id: p.id,
                nama: p.nama,
                harga_jual: p.harga_jual,
                stok: p.stok,
                foto: p.foto,
                tempQty: 1
            })),
            cart: @json($penjualan->itemPenjualans).map(item => ({
                id: item.produks_id,
                nama: item.produk ? item.produk.nama : 'Produk Dihapus',
                harga_jual: item.harga_satuan,
                qty: item.kuantitas
            })),
            metodePembayaran: '{{ strtolower($penjualan->metode_pembayaran) }}',
            statusTransaksi: '{{ strtolower($penjualan->status) == "selesai" ? "completed" : strtolower($penjualan->status) }}',
            
            get filteredProducts() {
                if (this.search === '') return this.products;
                return this.products.filter(p => p.nama.toLowerCase().includes(this.search.toLowerCase()));
            },

            addToCart(product) {
                let existingItem = this.cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.qty += product.tempQty;
                } else {
                    this.cart.push({
                        id: product.id,
                        nama: product.nama,
                        harga_jual: product.harga_jual,
                        qty: product.tempQty
                    });
                }
                product.tempQty = 1;
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            updateQty(index) {
                if (this.cart[index].qty <= 0) {
                    this.cart[index].qty = 1;
                }
            },

            executeSave() {
                this.isSaving = true;
                document.getElementById('form-update-penjualan').submit();
            },

            get totalBayar() {
                return this.cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
            }
        }
    }
</script>
@endsection