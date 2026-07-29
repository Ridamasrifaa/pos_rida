<?php

namespace App\Http\Controllers;

use App\Models\ItemPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemPenjualan $itempenjualan)
    {
        // Cek policy apakah user berhak menghapus item ini
        $this->authorize('delete', $itempenjualan);

        DB::transaction(function () use ($itempenjualan) {
            $produk = $itempenjualan->produk;
            $sale = $itempenjualan->penjualan;

            // 1. Kembalikan stok produk sejumlah kuantitas item yang dihapus
            if ($produk) {
                $produk->increment('stok', $itempenjualan->kuantitas);
            }

            // 2. Hapus item penjualan tersebut
            $itempenjualan->delete();

            // 3. Update total pembayaran di tabel penjualan secara otomatis
            if ($sale) {
                $sale->update([
                    'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal')
                ]);
            }
        });

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}