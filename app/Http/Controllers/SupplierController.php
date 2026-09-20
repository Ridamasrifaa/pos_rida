<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // Menampilkan daftar suplier dengan fitur pencarian & pagination
    public function index(Request $request)
    {
        $query = $request->input('search');

        $suppliers = Supplier::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%{$query}%")
                     ->orWhere('phone', 'like', "%{$query}%")
                     ->orWhere('address', 'like', "%{$query}%");
        })->latest()->paginate(10);

        // Jika request berasal dari AJAX (Live Search atau Pagination), kembalikan response JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('suppliers.partials.table', compact('suppliers'))->render(),
                'total' => $suppliers->total(),
                'pagination' => (string) $suppliers->links()
            ]);
        }

        return view('suppliers.index', compact('suppliers'));
    }

    // Menampilkan form tambah suplier
    public function create()
    {
        return view('suppliers.create');
    }

   // Menyimpan data suplier baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif', 
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Suplier berhasil ditambahkan!');
    }

    // Mengupdate data suplier
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif', // <-- TAMBAHKAN VALIDASI STATUS DI SINI
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Data suplier berhasil diupdate!');
    }

    // Menampilkan form edit suplier
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    // Menghapus data suplier
    public function destroy(Supplier $supplier)
    {
        // Cek apakah suplier masih digunakan di tabel produks
        if ($supplier->produks()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Suplier ini tidak dapat dihapus karena masih tercatat dalam data produk.');
        }

        $supplier->delete();
        
        return redirect()->route('suppliers.index')->with('success', 'Suplier berhasil dihapus!');
    }
   
}