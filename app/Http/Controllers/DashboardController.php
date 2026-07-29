<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaporanPenjualanService;
use App\Services\MonitoringStokService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct(
      protected LaporanPenjualanService $laporanPenjualanService,
      protected MonitoringStokService $stokService
    ) {}

   public function index()
    {
        $ringkasan = $this->laporanPenjualanService->ringkasanHariini();
        
        return view('dashboard', [
            'tanggalHariIni' => Carbon::now(),
            'ringkasan' => $ringkasan,
            // Panggil method dari service yang sudah kamu buat, bukan query manual
            'produkTerlaris' => $this->laporanPenjualanService->produkTerlarisHariini(),
            'produkStokRendah' => $this->stokService->produkStokRendah(),
            'produkStokHabis' => $this->stokService->produkStokHabis(),
        ]);
    }
}
