<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
    {
        // TAMBAHKAN ->with('role') DI SINI SUPAYA RELASINYA KEBACA
        $users = User::with('role')->latest()->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $dataReq = $request->validated();
        $data['name'] = $dataReq['name'];
        $data['email'] = $dataReq['email'];
        $data['password'] = Hash::make($dataReq['password']);
        $data['role_id'] = $dataReq['role_id'];

        User::create($data);
        return redirect()->route('admin.users')->with('success', 'User Berhasil Ditambahkan');
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
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role_id' => 'required',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }
    $user->role_id = $request->role_id;
    $user->save();

    return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui!');
}

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Cek apakah user punya relasi transaksi (mencegah error database)
        if (method_exists($user, 'penjualans') && $user->penjualans()->exists()) {
            return redirect()->route('admin.users')->with('error', 'User tidak dapat dihapus karena memiliki riwayat transaksi!');
        }

        // Eksekusi hapus data
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }
}
