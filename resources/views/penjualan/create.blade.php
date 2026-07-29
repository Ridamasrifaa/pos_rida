@extends('layouts.app')

@section('title', 'Tambah Penjualan - POS Rida')

@section('content')
<div x-data="posApp()" class="space-y-6 relative">



    <div class="flex justify-between items-center">
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
            
            <input type="text" x-model="search" placeholder="Cari produk..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500">

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="flex items-center justify-between p-3 border border-slate-100 rounded-2xl hover:bg-slate-50/50 transition">
                        <div>
                            <div class="font-bold text-slate-800 text-sm" x-text="product.nama"></div>
                            <div class="text-xs text-slate-400" x-text="'Stok: ' + product.stok"></div>
                            <div class="text-xs text-rose-600 font-semibold" x-text="'Rp ' + Number(product.harga_jual).toLocaleString('id-ID')"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" x-model.number="product.tempQty" min="1" class="w-16 px-2 py-1 text-center bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none">
                            <button @click="addToCart(product)" type="button" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
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
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <!-- HANYA MUNCUL JIKA ADMIN -->
                                    @php
                                        $isAdmin = auth()->check() && auth()->user()->role && strtoupper(auth()->user()->role->name) === 'ADMIN';
                                    @endphp
                                    @if($isAdmin)
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in cart" :key="index">
                                    <tr>
                                        <td class="font-medium text-slate-700" x-text="item.nama"></td>
                                        <td x-text="'Rp ' + Number(item.harga_jual).toLocaleString('id-ID')"></td>
                                        <td>
                                            <input type="number" x-model.number="item.qty" @change="updateQty(index)" min="1" class="w-12 px-1 py-0.5 text-center border rounded-lg text-xs">
                                        </td>
                                        <td class="font-semibold" x-text="'Rp ' + Number(item.harga_jual * item.qty).toLocaleString('id-ID')"></td>
                                        
                                        <!-- HANYA MUNCUL JIKA ADMIN -->
                                        @if($isAdmin)
                                        <td>
                                            <button @click="removeFromCart(index)" type="button" class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[10px] font-bold transition">Hapus</button>
                                        </td>
                                        @endif
                                    </tr>
                                </template>
                                <template x-if="cart.length === 0">
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-6 text-slate-400">Keranjang masih kosong</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bagian Pembayaran & Tombol Aksi (Checkout vs Simpan Draft / OPEN) -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div>
                        <div class="text-xl font-extrabold text-slate-800 mb-2" x-text="'Rp ' + totalBayar.toLocaleString('id-ID')"></div>
                        
                        <!-- Pilihan Metode Pembayaran -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran" x-model="metodePembayaran" class="select select-bordered w-full rounded-xl bg-slate-50 border-slate-200 text-sm">
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    <!-- Input tersembunyi yang dikirim ke Controller -->
                    <input type="hidden" name="total_pembayaran" x-model="totalBayar">
                    <input type="hidden" name="items" x-model="JSON.stringify(cart)">
                    <!-- Input status dinamis (bisa 'COMPLETED' atau 'OPEN') -->
                    <input type="hidden" name="status" x-model="statusTransaksi">

                    <!-- Tombol Aksi dengan Loading State -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Tombol Simpan Draft -->
                        <button type="submit" @click="statusTransaksi = 'OPEN'" :disabled="cart.length === 0 || isSubmitting" class="w-full py-3 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-200 text-white rounded-2xl font-bold text-sm shadow-sm transition flex items-center justify-center gap-2">
                            <span x-show="isSubmitting && statusTransaksi === 'OPEN'" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="isSubmitting && statusTransaksi === 'OPEN' ? 'Memproses...' : 'Simpan Draft'"></span>
                        </button>

                        <!-- Tombol Checkout -->
                        <button type="submit" @click="statusTransaksi = 'COMPLETED'" :disabled="cart.length === 0 || isSubmitting" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-200 text-white rounded-2xl font-bold text-sm shadow-sm transition flex items-center justify-center gap-2">
                            <span x-show="isSubmitting && statusTransaksi === 'COMPLETED'" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="isSubmitting && statusTransaksi === 'COMPLETED' ? 'Memproses...' : 'Checkout'"></span>
                        </button>
                    </div>

                    <!-- TOMBOL BATAL TRANSAKSI HANYA MUNCUL UNTUK ADMIN -->
                    @if($isAdmin)
                    <a href="{{ route('penjualan.index') }}" class="block text-center w-full py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl font-bold text-sm transition">
                        Batalkan Transaksi
                    </a>
                    @endif
                </div>
            </form>
        </div>

    </div>

    <!-- MODAL POPUP PERINGATAN (Tema Rose / Merah & Ikon SVG) -->
    <template x-if="showModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 text-center space-y-4">
                <!-- Ikon Peringatan SVG (Warna Merah) -->
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <!-- Judul & Pesan -->
                <div class="space-y-1">
                    <h3 class="font-bold text-lg text-slate-800">Peringatan Stok</h3>
                    <p class="text-sm text-slate-500" x-text="errorMessage"></p>
                </div>

                <!-- Tombol Tutup (Tema Merah) -->
                <button @click="showModal = false" type="button" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-sm transition">
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
            isSubmitting: false, // State untuk loading saat submit
            products: @json($products).map(p => ({
                id: p.id,
                nama: p.nama,
                harga_jual: p.harga_jual,
                stok: p.stok,
                tempQty: 1
            })),
            cart: [],
            metodePembayaran: 'cash',
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