<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $products = DB::table('produks')->get();
        $sales = DB::table('penjualans')->latest()->get();

        return view('sales.index', compact('products', 'sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required'
        ]);

        $items = json_decode($request->input('items'), true);

        DB::beginTransaction();
        try {
            $totalPembayaran = collect($items)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // Sesuai migration: total_pembayaran, metode_pembayaran, status
            $penjualanId = DB::table('penjualans')->insertGetId([
                'user_id' => auth()->id(),
                'total_pembayaran' => $totalPembayaran,
                'metode_pembayaran' => 'Cash', // Default metode pembayaran
                'status' => 'OPEN',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($items as $item) {
                DB::table('item_penjualans')->insert([
                    'penjualans_id' => $penjualanId,
                    'produks_id' => $item['id'],
                    'kuantitas' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi.');
        }
    }

    public function destroy($id)
    {
        $sale = DB::table('penjualans')->where('id', $id)->first();

        if (!$sale) {
            return back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        if ($sale->status === 'COMPLETED') {
            return back()->with('error', 'Transaksi yang sudah selesai (COMPLETED) tidak dapat dihapus!');
        }

        // Hapus item terkait terlebih dahulu agar tidak kena foreign key constraint
        DB::table('item_penjualans')->where('penjualans_id', $id)->delete();
        DB::table('penjualans')->where('id', $id)->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}