<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\ItemPenjualan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;


class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = auth()->user();
        
        $userRole = $user->role ? strtoupper($user->role->name) : '';

        $query = Penjualan::with('user');

        if ($userRole !== 'ADMIN') {
            $query->where('user_id', $user->id);
        }

        $penjualans = $query->when($search, function ($q, $search) {
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', 'like', "%{$search}%")
                           ->orWhere('status', 'like', "%{$search}%")
                           ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                           ->orWhereHas('user', function ($userQuery) use ($search) {
                               $userQuery->where('name', 'like', "%{$search}%");
                           });
                });
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('penjualan.partials.table-rows', compact('penjualans'))->render(),
                'total' => $penjualans->total(),
                'pagination' => (string) $penjualans->links()
            ]);
        }

        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $products = Produk::all();
        return view('penjualan.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required',
            'total_pembayaran' => 'required|numeric',
            'uang_bayar' => 'nullable|numeric',
            'items' => 'required',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Hitung subtotal dan diskon 10% jika >= 1.000.000 di backend (keamanan data)
        $subtotal = collect($items)->sum(function ($item) {
            return $item['harga_jual'] * $item['qty'];
        });

        $diskon = $subtotal >= 1000000 ? $subtotal * 0.10 : 0;
        $calculatedTotal = $subtotal - $diskon;

        DB::beginTransaction();
        try {
            $penjualan = Penjualan::create([
                'user_id' => auth()->id(),
                'total_pembayaran' => $calculatedTotal,
                'uang_bayar' => $request->uang_bayar ?? 0,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->status,
            ]);

            foreach ($items as $item) {
                ItemPenjualan::create([
                    'penjualans_id' => $penjualan->id,
                    'produks_id' => $item['id'],          
                    'kuantitas' => $item['qty'],          
                    'harga_satuan' => $item['harga_jual'], 
                    'subtotal' => $item['harga_jual'] * $item['qty'], 
                ]);

                $produk = Produk::find($item['id']);
                if ($produk) {
                    $produk->stok -= $item['qty'];
                    $produk->save();
                }
            }

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil diselesaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['user', 'itemPenjualans.produk'])->findOrFail($id);

        return view('penjualan.show', compact('penjualan'));
    }

    public function edit($id)
    {
        $userRole = auth()->user()->role ? strtoupper(auth()->user()->role->name) : '';
        if ($userRole !== 'ADMIN') {
            abort(403, 'Unauthorized action. Kasir tidak diizinkan mengedit transaksi.');
        }

        $penjualan = Penjualan::with('itemPenjualans.produk', 'user')->findOrFail($id);
        $products = Produk::all();
        
        return view('penjualan.edit', compact('penjualan', 'products'));
    }

    public function update(Request $request, $id)
    {
        $userRole = auth()->user()->role ? strtoupper(auth()->user()->role->name) : '';
        if ($userRole !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'metode_pembayaran' => 'required',
            'status' => 'required',
            'total_pembayaran' => 'required|numeric',
            'uang_bayar' => 'nullable|numeric',
            'items' => 'required',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items)) {
            return redirect()->back()->with('error', 'Keranjang item tidak boleh kosong!');
        }

        // Hitung ulang diskon di backend untuk update
        $subtotal = collect($items)->sum(function ($item) {
            return $item['harga_jual'] * $item['qty'];
        });

        $diskon = $subtotal >= 1000000 ? $subtotal * 0.10 : 0;
        $calculatedTotal = $subtotal - $diskon;

        DB::beginTransaction();
        try {
            $penjualan = Penjualan::with('itemPenjualans')->findOrFail($id);
            
            foreach ($penjualan->itemPenjualans as $oldItem) {
                $produk = Produk::find($oldItem->produks_id);
                if ($produk) {
                    $produk->stok += $oldItem->kuantitas;
                    $produk->save();
                }
            }

            $penjualan->itemPenjualans()->delete();

            $penjualan->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->status,
                'total_pembayaran' => $calculatedTotal,
                'uang_bayar' => $request->uang_bayar ?? 0,
            ]);

            foreach ($items as $item) {
                ItemPenjualan::create([
                    'penjualans_id' => $penjualan->id,
                    'produks_id' => $item['id'],          
                    'kuantitas' => $item['qty'],          
                    'harga_satuan' => $item['harga_jual'], 
                    'subtotal' => $item['harga_jual'] * $item['qty'], 
                ]);

                $produk = Produk::find($item['id']);
                if ($produk) {
                    $produk->stok -= $item['qty'];
                    $produk->save();
                }
            }

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $userRole = auth()->user()->role ? strtoupper(auth()->user()->role->name) : '';
        if ($userRole !== 'ADMIN') {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menghapus transaksi.');
        }

        $penjualan = Penjualan::with('itemPenjualans')->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($penjualan->itemPenjualans as $item) {
                $produk = Produk::find($item->produks_id);
                if ($produk) {
                    $produk->stok += $item->kuantitas;
                    $produk->save();
                }
            }

            $penjualan->itemPenjualans()->delete();
            $penjualan->delete();

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}