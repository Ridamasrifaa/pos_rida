<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanService
{
    public function ringkasanHariini(): array
    {
        $data = DB::table('penjualans')
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'COMPLETED')
            ->selectRaw('COUNT(*) as total_transaksi, SUM(total_pembayaran) as total_penjualan, SUM(CASE WHEN metode_pembayaran = "CASH" THEN total_pembayaran ELSE 0 END) as total_cash, SUM(CASE WHEN metode_pembayaran != "CASH" THEN total_pembayaran ELSE 0 END) as total_non_tunai')
            ->first();

        return [
            'total_transaksi' => $data->total_transaksi ?? 0,
            'total_penjualan' => $data->total_penjualan ?? 0,
            'total_cash' => $data->total_cash ?? 0,
            'total_non_tunai' => $data->total_non_tunai ?? 0,
        ];
    }
public function produkTerlarisHariini(int $limit = 5)
    {
        return DB::table('item_penjualans')
            ->join('penjualans', 'penjualans.id', '=', 'item_penjualans.penjualans_id')
            ->join('produks', 'produks.id', '=', 'item_penjualans.produks_id')
            ->whereDate('penjualans.created_at', Carbon::today())
            ->where('penjualans.status', 'COMPLETED')
            ->select('produks.id', 'produks.nama', 'produks.stok', DB::raw('SUM(item_penjualans.kuantitas) as total_terjual'))
            ->groupBy('produks.id', 'produks.nama', 'produks.stok')
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->get();
    }

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
}