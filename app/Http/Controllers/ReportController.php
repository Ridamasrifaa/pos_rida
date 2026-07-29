<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // 1. Rekap Bulanan
    public function monthlyIndex(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $monthlyData = Penjualan::selectRaw('MONTH(created_at) as month, SUM(total_pembayaran) as total_omzet, COUNT(*) as total_transaksi')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        return view('admin.reports.monthly', compact('monthlyData', 'year'));
    }

    // 2. Rekap Mingguan
    // 2. Rekap Mingguan
    public function weeklyIndex(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $rawWeekly = Penjualan::selectRaw('WEEK(created_at, 1) as week, SUM(total_pembayaran) as total_omzet, COUNT(*) as total_transaksi')
            ->whereYear('created_at', $year)
            ->groupBy('week')
            ->orderBy('week', 'ASC')
            ->get();

        // Mengolah rentang tanggal menggunakan setISODate agar aman dan akurat
        $weeklyData = $rawWeekly->map(function ($item) use ($year) {
            $weekNum = (int) $item->week;
            
            // Membuat instance Carbon berdasarkan tahun dan nomor minggu (ISO week)
            $date = Carbon::now()->setISODate($year, $weekNum);
            
            $startDate = $date->startOfWeek()->translatedFormat('d M Y');
            $endDate = $date->endOfWeek()->translatedFormat('d M Y');

            return [
                'week_number' => $weekNum,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_transaksi' => $item->total_transaksi,
                'total_omzet' => $item->total_omzet,
            ];
        });

        return view('admin.reports.weekly', compact('weeklyData', 'year'));
    }

    // 3. Rekap Harian (Detail per Bulan)
    public function dailyDetail($year, $month)
    {
        $dailyData = Penjualan::selectRaw('DAY(created_at) as day, SUM(total_pembayaran) as total_omzet, COUNT(*) as total_transaksi')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->get();

        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        return view('admin.reports.daily', compact('dailyData', 'year', 'month', 'monthName'));
    }
}