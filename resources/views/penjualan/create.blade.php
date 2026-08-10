@extends('layouts.app')

@section('title', 'Tambah Penjualan - POS Rida')

@section('content')
<!-- Animasi Fade In & Slide Up Saat Halaman Pertama Kali Dibuka -->
<div x-data="posApp()" 
     class="space-y-6 relative opacity-0 translate-y-4 transition-all duration-700 ease-out"
     x-init="$el.classList.remove('opacity-0', 'translate-y-4')">

    <!-- Card Header / Judul Halaman -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Penjualan</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pilih produk di sebelah kiri untuk dimasukkan ke keranjang kasir.</p>
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

        <!-- KOLOM KANAN: Keranjang & Form Checkout / Simpan Draft -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between space-y-6">
            <form action="{{ route('penjualan.store') }}" method="POST" @submit="isSubmitting = true" class="flex flex-col justify-between h-full space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-800">Keranjang Belanja</h3>
                    </div>

                    <div class="overflow-x-auto border border-slate-100 rounded-2xl max-h-[300px] overflow-y-auto">
                        <table class="table w-full text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400">
                                    <th class="py-3 px-3">Produk</th>
                                    <th class="py-3 px-2">Harga</th>
                                    <th class="py-3 px-2">Qty</th>
                                    <th class="py-3 px-2">Subtotal</th>
                                    @php
                                        $isAdmin = auth()->check() && auth()->user()->role && strtoupper(auth()->user()->role->name) === 'ADMIN';
                                    @endphp
                                    @if($isAdmin)
                                    <th class="py-3 px-2">Aksi</th>
                                    @endif
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
                                        
                                        @if($isAdmin)
                                        <td class="py-3 px-2">
                                            <button @click="removeFromCart(index)" type="button" class="px-2 py-1 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg text-[10px] font-bold transition">Hapus</button>
                                        </td>
                                        @endif
                                    </tr>
                                </template>
                                <template x-if="cart.length === 0">
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-8 text-slate-400">Keranjang masih kosong</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bagian Pembayaran & Tombol Aksi -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div>
                        <div class="text-xs text-slate-400 font-medium">Total Pembayaran</div>
                        <div class="text-2xl font-extrabold text-slate-800 mb-3" x-text="'Rp ' + totalBayar.toLocaleString('id-ID')"></div>
                        
                        <!-- Pilihan Metode Pembayaran Model Tombol Kartu Modern -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-600">Metode Pembayaran <span class="text-rose-500">*</span></label>
                            
                            <!-- Hidden input untuk menyimpan value metode pembayaran ke Controller -->
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
                    </div>

                    <!-- Input tersembunyi yang dikirim ke Controller -->
                    <input type="hidden" name="total_pembayaran" x-model="totalBayar">
                    <input type="hidden" name="items" x-model="JSON.stringify(cart)">
                    <input type="hidden" name="status" x-model="statusTransaksi">

                    <!-- Tombol Aksi dengan Loading State & Validasi Metode Pembayaran -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Tombol Simpan Draft -->
                        <button type="submit" @click="statusTransaksi = 'OPEN'" :disabled="cart.length === 0 || !metodePembayaran || isSubmitting" class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-100 disabled:text-slate-400 text-white rounded-2xl font-bold text-sm shadow-sm shadow-amber-100 transition flex items-center justify-center gap-2 transform active:scale-95">
                            <span x-show="isSubmitting && statusTransaksi === 'OPEN'" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="isSubmitting && statusTransaksi === 'OPEN' ? 'Memproses...' : 'Simpan Draft'"></span>
                        </button>

                        <!-- Tombol Checkout -->
                        <button type="submit" @click="statusTransaksi = 'COMPLETED'" :disabled="cart.length === 0 || !metodePembayaran || isSubmitting" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-100 disabled:text-slate-400 text-white rounded-2xl font-bold text-sm shadow-sm shadow-emerald-100 transition flex items-center justify-center gap-2 transform active:scale-95">
                            <span x-show="isSubmitting && statusTransaksi === 'COMPLETED'" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="isSubmitting && statusTransaksi === 'COMPLETED' ? 'Memproses...' : 'Checkout'"></span>
                        </button>
                    </div>

                    @if($isAdmin)
                    <a href="{{ route('penjualan.index') }}" class="block text-center w-full py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl font-bold text-sm transition">
                        Batalkan Transaksi
                    </a>
                    @endif
                </div>
            </form>
        </div>

    </div>

    <!-- MODAL POPUP PERINGATAN -->
    <template x-if="showModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 transition-opacity"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 text-center space-y-4 transform transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <div class="space-y-1">
                    <h3 class="font-bold text-lg text-slate-800">Peringatan Stok</h3>
                    <p class="text-sm text-slate-500 leading-relaxed" x-text="errorMessage"></p>
                </div>

                <button @click="showModal = false" type="button" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-sm shadow-rose-100 transition transform active:scale-95">
                    Mengerti
                </button>
            </div>
        </div>
    </template>
</div>

<!-- Script Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function posApp() {
        return {
            search: '',
            errorMessage: '',
            showModal: false,
            isSubmitting: false,
            products: @json($products).map(p => ({
                id: p.id,
                nama: p.nama,
                harga_jual: p.harga_jual,
                stok: p.stok,
                foto: p.foto,
                tempQty: 1
            })),
            cart: [],
            metodePembayaran: '',
            statusTransaksi: 'COMPLETED',
            
            get filteredProducts() {
                if (this.search === '') return this.products;
                return this.products.filter(p => p.nama.toLowerCase().includes(this.search.toLowerCase()));
            },

            addToCart(product) {
                if (product.tempQty > product.stok) {
                    this.errorMessage = `Stok produk "${product.nama}" tidak mencukupi! Sisa stok: ${product.stok}`;
                    this.showModal = true;
                    return;
                }

                let existingItem = this.cart.find(item => item.id === product.id);
                let currentQtyInCart = existingItem ? existingItem.qty : 0;
                let totalRequested = currentQtyInCart + product.tempQty;

                if (totalRequested > product.stok) {
                    this.errorMessage = `Jumlah melebihi stok tersedia! Sisa stok: ${product.stok - currentQtyInCart}`;
                    this.showModal = true;
                    return;
                }

                if (existingItem) {
                    existingItem.qty += product.tempQty;
                } else {
                    this.cart.push({
                        id: product.id,
                        nama: product.nama,
                        harga_jual: product.harga_jual,
                        stok: product.stok,
                        qty: product.tempQty
                    });
                }
                product.tempQty = 1;
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            updateQty(index) {
                let item = this.cart[index];
                if (item.qty <= 0) {
                    item.qty = 1;
                }
                if (item.qty > item.stok) {
                    this.errorMessage = `Stok maksimal untuk "${item.nama}" adalah ${item.stok}`;
                    this.showModal = true;
                    item.qty = item.stok;
                }
            },

            get totalBayar() {
                return this.cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
            }
        }
    }
</script>
@endsection