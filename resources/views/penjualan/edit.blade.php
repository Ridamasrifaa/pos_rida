@extends('layouts.app')

@section('title', 'Edit Transaksi #' . $penjualan->id . ' - POS Rida')

@section('content')
<div x-data="editPosApp()" class="space-y-6">
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
            
            <input type="text" x-model="search" placeholder="Cari produk..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500">

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="flex items-center justify-between p-3 border border-slate-100 rounded-2xl hover:bg-slate-50/50 transition">
                        <div>
                            <div class="font-bold text-slate-800 text-sm" x-text="product.nama"></div>
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
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
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
                                        <td>
                                            <button @click="removeFromCart(index)" type="button" class="px-2 py-1 bg-rose-600 text-white rounded-lg text-[10px] font-bold">Hapus</button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="cart.length === 0">
                                    <tr>
                                        <td colspan="5" class="text-center py-6 text-slate-400">Belum ada item dipilih</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bagian Pembayaran & Pengaturan Status -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div>
                        <div class="text-sm font-semibold text-slate-700 mb-1">Total Pembayaran</div>
                        <div class="text-xl font-extrabold text-slate-800 mb-3" x-text="'Rp ' + totalBayar.toLocaleString('id-ID')"></div>
                        
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" x-model="metodePembayaran" class="select select-bordered w-full rounded-xl bg-slate-50 border-slate-200 text-sm mb-3">
                            <option value="cash">CASH</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">TRANSFER</option>
                        </select>

                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Transaksi</label>
                        <select name="status" x-model="statusTransaksi" class="select select-bordered w-full rounded-xl bg-slate-50 border-slate-200 text-sm">
                            <option value="completed">COMPLETED</option>
                            <option value="open">OPEN</option>
                        </select>
                    </div>

                    <!-- Input tersembunyi untuk dikirim ke Controller -->
                    <input type="hidden" name="total_pembayaran" x-model="totalBayar">
                    <input type="hidden" name="items" x-model="JSON.stringify(cart)">

                    <!-- Tombol Aksi (Simpan Perubahan, Hapus Transaksi, & Batalkan Transaksi) -->
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="showSaveModal = true" :disabled="cart.length === 0" class="w-full py-3 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-200 text-white rounded-2xl font-bold text-sm shadow-sm transition active:scale-95">
                                Simpan Perubahan
                            </button>

                            @if(strtolower($penjualan->status) === 'open')
                                <button type="button" @click="showDeleteModal = true" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-sm transition active:scale-95">
                                    Hapus Transaksi
                                </button>
                            @else
                                <a href="{{ route('penjualan.index') }}" class="block text-center w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition leading-loose active:scale-95">
                                    Batalkan Transaksi
                                </a>
                            @endif
                        </div>

                        @if(strtolower($penjualan->status) === 'open')
                            <a href="{{ route('penjualan.index') }}" class="block text-center w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition active:scale-95">
                                Batalkan Transaksi
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Card Modal Konfirmasi Simpan Perubahan -->
            <div x-show="showSaveModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" x-transition.opacity>
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
                <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4" x-transition.opacity>
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