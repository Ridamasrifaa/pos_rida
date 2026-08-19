<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $jenis = Jenis::with('user')
            ->when($query, function($q) use ($query) {
                $q->where('nama_jenis', 'like', '%' . $query . '%');
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('jenis.partials.table', compact('jenis'))->render(),
                'total' => $jenis->total(),
                'pagination' => (string) $jenis->links()
            ]);
        }

        return view('jenis.index', compact('jenis'));
    }

    public function create()
    {
        return view('jenis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis',
        ]);

        Jenis::create([
            'user_id' => Auth::id(),
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil ditambahkan!');
    }

    public function edit(Jenis $jenis)
    {
        return view('jenis.edit', compact('jenis'));
    }

    public function update(Request $request, Jenis $jenis)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,' . $jenis->id,
        ]);

        $jenis->update([
            'user_id' => Auth::id(),
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil diperbarui!');
    }

    public function destroy(Jenis $jenis)
    {
        if ($jenis->produks()->count() > 0) {
            return redirect()->route('jenis.index')->with('error', 'Jenis produk tidak bisa dihapus karena masih digunakan oleh produk!');
        }

        $jenis->delete();
        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil dihapus!');
    }
}