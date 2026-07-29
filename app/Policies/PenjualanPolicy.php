<?php

namespace App\Policies;

use App\Models\Penjualan;
use App\Models\User;

class PenjualanPolicy
{
    // Hanya admin dan status open yang bisa menghapus transaksi
    public function delete(User $user, Penjualan $penjualan): bool
    {
        $roleName = $user->role ? strtolower($user->role->name) : '';
        $status = strtolower($penjualan->status);

        return $roleName === 'admin' && $status === 'open';
    }

    // Admin dan kasir bisa melihat detail transaksi
    public function view(User $user, Penjualan $penjualan): bool
    {
        $roleName = $user->role ? strtolower($user->role->name) : '';

        return in_array($roleName, ['admin', 'kasir']);
    }

    // Transaksi dengan status OPEN dapat diedit oleh admin
    public function update(User $user, Penjualan $penjualan): bool
    {
        $roleName = $user->role ? strtolower($user->role->name) : '';
        $status = strtolower($penjualan->status);

        return $roleName === 'admin' && $status === 'open';
    }
}