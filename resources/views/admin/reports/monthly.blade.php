@extends('layouts.app')

@section('title', 'Rekap Bulanan - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fade-in" x-data="monthlyReportApp()">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Rekap Bulanan</h1>
            <p class="text-sm text-slate-500 mt-0.5">Analisis tren dan pergerakan omzet toko tahun {{ $year }}.</p>
        </div>

        <!-- Navigasi Tab Kotak Modern -->
        <div class="inline-flex p-1.5 bg-slate-100 rounded-2xl border border-slate-200/50 self-start sm:self-auto">
            <a href="{{ route('admin.reports.monthly') }}" class="px-5 py-2.5 bg-white text-rose-600 rounded-xl text-xs font-bold shadow-sm transition-all duration-200">
                Bulanan
            </a>
            <a href="{{ route('admin.reports.weekly') }}" class="px-5 py-2.5 text-slate-500 hover:text-slate-800 rounded-xl text-xs font-semibold transition-all duration-200">
                Mingguan
            </a>
        </div>
    </div>

    <!-- Grafik Tren Omzet Full-Width (Lebar Penuh) -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Grafik Omzet Bulanan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Visualisasi pendapatan toko dari Januari sampai Desember</p>
            </div>
        </div>
        <div class="relative w-full h-80">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Tabel Rekap Bulanan -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden space-y-4 p-6 transition-all duration-300 hover:shadow-md">
        <h3 class="font-bold text-lg text-slate-800">Daftar Bulan</h3>
        
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="bg-transparent py-3">Bulan</th>
                        <th class="bg-transparent py-3">Jumlah Transaksi</th>
                        <th class="bg-transparent py-3">Total Omzet</th>
                        <th class="bg-transparent py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600">
                    @foreach($monthlyData as $row)
                        @php
                            $namaBulan = \Carbon\Carbon::createFromDate($year, $row->month, 1)->translatedFormat('F');
                        @endphp
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="font-semibold text-slate-800 py-4">{{ $namaBulan }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium">
                                    {{ $row->total_transaksi }} Transaksi
                                </span>
                            </td>
                            <td class="font-bold text-emerald-600 py-4">Rp {{ number_format($row->total_omzet, 0, ',', '.') }}</td>
                            <td class="text-center py-4">
                                <a href="{{ route('admin.reports.daily', ['year' => $year, 'month' => $row->month]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition shadow-sm shadow-rose-100 hover:scale-105">
                                    Detail 
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if($monthlyData->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-10 text-slate-400 text-xs">Belum ada data penjualan tahun ini.</td>
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
    function monthlyReportApp() {
        return {}
    }

    const rawMonths = @json($monthlyData);
    let monthsLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    let omzetData = new Array(12).fill(0);

    rawMonths.forEach(item => {
        omzetData[item.month - 1] = item.total_omzet;
    });

    // Grafik Tren Omzet Full-Width
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const lineGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
    lineGradient.addColorStop(0, 'rgba(225, 29, 72, 0.25)');
    lineGradient.addColorStop(1, 'rgba(225, 29, 72, 0.0)');

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: monthsLabel,
            datasets: [{
                label: 'Omzet',
                data: omzetData,
                borderColor: 'rgba(225, 29, 72, 1)',
                backgroundColor: lineGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: 'rgba(225, 29, 72, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            return ' Omzet: Rp ' + (context.raw || 0).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 12 } }
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