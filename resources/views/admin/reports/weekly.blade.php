@extends('layouts.app')

@section('title', 'Rekap Mingguan - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fade-in" x-data="weeklyReportApp()">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Rekap Mingguan</h1>
            <p class="text-sm text-slate-500 mt-0.5">Analisis omzet toko per minggu berdasarkan rentang tanggal tahun {{ $year }}.</p>
        </div>

        <!-- Navigasi Tab Kotak Modern -->
        <div class="inline-flex p-1.5 bg-slate-100 rounded-2xl border border-slate-200/50 self-start sm:self-auto">
            <a href="{{ route('admin.reports.monthly') }}" class="px-5 py-2.5 text-slate-500 hover:text-slate-800 rounded-xl text-xs font-semibold transition-all duration-200">
                Bulanan
            </a>
            <a href="{{ route('admin.reports.weekly') }}" class="px-5 py-2.5 bg-white text-rose-600 rounded-xl text-xs font-bold shadow-sm transition-all duration-200">
                Mingguan
            </a>
        </div>
    </div>

    <!-- Grafik Tren Omzet Mingguan -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Grafik Omzet Mingguan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Visualisasi naik turunnya pendapatan toko tiap minggu dalam tahun {{ $year }}</p>
            </div>
        </div>
        <div class="relative w-full h-80">
            <canvas id="weeklyTrendChart"></canvas>
        </div>
    </div>

    <!-- Tabel Rekap Mingguan -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden space-y-4 p-6 transition-all duration-300 hover:shadow-md">
        <h3 class="font-bold text-lg text-slate-800">Daftar Periode Minggu</h3>
        
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="bg-transparent py-3">Minggu Ke-</th>
                        <th class="bg-transparent py-3">Periode Tanggal</th>
                        <th class="bg-transparent py-3">Jumlah Transaksi</th>
                        <th class="bg-transparent py-3">Total Omzet</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600">
                    @foreach($weeklyData as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="font-semibold text-slate-800 py-4">Minggu ke-{{ $row['week_number'] }}</td>
                            <td class="py-4 text-xs font-medium text-slate-500">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg">
                                    {{ $row['start_date'] }} – {{ $row['end_date'] }}
                                </span>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-medium">
                                    {{ $row['total_transaksi'] }} Transaksi
                                </span>
                            </td>
                            <td class="font-bold text-emerald-600 py-4">Rp {{ number_format($row['total_omzet'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @if($weeklyData->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-10 text-slate-400 text-xs">Belum ada data penjualan mingguan tahun ini.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Style Tambahan untuk Animasi Halus -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<!-- CDN Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function weeklyReportApp() {
        return {}
    }

    const rawWeekly = @json($weeklyData);
    
    let weeksLabel = rawWeekly.map(item => 'Minggu ' + item.week_number);
    let weeklyOmzet = rawWeekly.map(item => item.total_omzet);

    const weeklyCtx = document.getElementById('weeklyTrendChart').getContext('2d');
    
    const barGradient = weeklyCtx.createLinearGradient(0, 0, 0, 300);
    barGradient.addColorStop(0, 'rgba(225, 29, 72, 0.9)');
    barGradient.addColorStop(1, 'rgba(225, 29, 72, 0.4)');

    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: weeksLabel,
            datasets: [{
                label: 'Omzet Mingguan',
                data: weeklyOmzet,
                backgroundColor: barGradient,
                borderColor: 'rgba(225, 29, 72, 1)',
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 32, // Membatasi lebar maksimum batang agar tetap ramping & rapi
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 20, bottom: 10, left: 10, right: 10 }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 14,
                    cornerRadius: 12,
                    callbacks: {
                        title: function(context) {
                            return 'Periode Minggu ke-' + rawWeekly[context[0].dataIndex].week_number;
                        },
                        label: function(context) {
                            let item = rawWeekly[context.dataIndex];
                            return [
                                ' 💰 Omzet: Rp ' + (context.raw || 0).toLocaleString('id-ID'),
                                ' 🛒 Transaksi: ' + item.total_transaksi + ' Transaksi',
                                ' 📅 Tgl: ' + item.start_date + ' s.d. ' + item.end_date
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 12, weight: '600' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', borderDash: [4, 4] },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        callback: function(value) {
                            if (value === 0) return '0';
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1).replace('.0', '') + ' Juta';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + ' Ribu';
                            }
                            return value;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection