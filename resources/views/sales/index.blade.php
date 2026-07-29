<div x-data="cartSystem()" class="p-6 space-y-6">
    <!-- Bagian Atas: Grid untuk Produk dan Keranjang Belanja -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Kolom Daftar Produk -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-3">Daftar Produk</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($products as $product)
                    @php
                        $pId = $product->id ?? ($product->id_produk ?? 1);
                        $pName = $product->nama ?? ($product->name ?? ($product->nama_produk ?? 'Produk'));
                        $pPrice = $product->harga ?? ($product->price ?? ($product->harga_jual ?? 0));
                    @endphp
                    <div class="p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition" 
                         @click="addToCart({ id: {{ $pId }}, name: '{{ addslashes($pName) }}', price: {{ $pPrice }} })">
                        <p class="font-semibold">{{ $pName }}</p>
                        <p class="text-sm text-gray-600">Rp {{ number_format($pPrice, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Kolom Keranjang Belanja (Cart) -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-3">Keranjang Belanja</h3>
            
            <template x-if="cart.length === 0">
                <p class="text-gray-500 text-sm">Keranjang masih kosong.</p>
            </template>

            <ul class="divide-y mb-4">
                <template x-for="(item, index) in cart" :key="item.id">
                    <li class="py-2 flex justify-between items-center">
                        <div>
                            <p class="font-medium" x-text="item.name"></p>
                            <p class="text-xs text-gray-500" x-text="'Rp ' + item.price.toLocaleString() + ' x ' + item.quantity"></p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" @click="decreaseQty(index)" class="px-2 bg-gray-200 rounded font-bold">-</button>
                            <span x-text="item.quantity" class="px-2"></span>
                            <button type="button" @click="increaseQty(index)" class="px-2 bg-gray-200 rounded font-bold">+</button>
                        </div>
                    </li>
                </template>
            </ul>

            <div class="border-t pt-3 font-bold flex justify-between mb-4">
                <span>Total:</span>
                <span x-text="'Rp ' + totalHarga.toLocaleString()"></span>
            </div>

            <!-- Form untuk Kirim ke Backend -->
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="items" x-model="JSON.stringify(cart)">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition" :disabled="cart.length === 0">
                    Simpan Transaksi
                </button>
            </form>
        </div>

    </div>

    <!-- Bagian Bawah: Tabel Riwayat / Daftar Transaksi Penjualan -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Daftar Transaksi Penjualan</h2>

        <!-- Menampilkan Pesan Notifikasi Sukses / Error -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $sale->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sale->status === 'COMPLETED' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $sale->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if($sale->status === 'OPEN')
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic">Terkunci (Completed)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function cartSystem() {
    return {
        cart: [],
        addToCart(product) {
            let existing = this.cart.find(item => item.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({ id: product.id, name: product.name, price: product.price, quantity: 1 });
            }
        },
        increaseQty(index) {
            this.cart[index].quantity++;
        },
        decreaseQty(index) {
            if (this.cart[index].quantity > 1) {
                this.cart[index].quantity--;
            } else {
                this.cart.splice(index, 1);
            }
        },
        get totalHarga() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }
    }
}
</script>