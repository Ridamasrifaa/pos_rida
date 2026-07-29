@extends('layouts.app')

@section('title', 'Rincian Harian - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fade-in" x-data="dailyReportApp()">

    <!-- Header Halaman & Tombol Kembali -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Rincian Harian: {{ $monthName }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Daftar transaksi dan grafik harian secara rinci selama bulan ini.</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.monthly', ['year' => $year]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                Kembali ke Bulanan
            </a>
        </div>
    </div>

    <!-- Ringkasan Singkat (Cards) -->
    @php
        $totalOmzetBulanIni = $dailyData->sum('total_omzet');
        $totalTransaksiBulanIni = $dailyData->sum('total_transaksi');
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- Card Total Omzet -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all duration-300 hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Omzet Bulan Ini</p>
                <h3 class="text-xl font-extrabold text-slate-800 mt-0.5">Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card Total Transaksi -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all duration-300 hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-xl font-extrabold text-slate-800 mt-0.5">{{ $totalTransaksiBulanIni }} Transaksi</h3>
            </div>
        </div>

    </div>

    <!-- Grafik Area Omzet Harian -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Grafik Area Omzet Harian</h3>
                <p class="text-xs text-slate-400 mt-0.5">Visualisasi diagram area pendapatan harian selama bulan {{ $monthName }}</p>
            </div>
        </div>
        <div class="relative w-full h-80">
            <canvas id="dailyTrendChart"></canvas>
        </div>
    </div>

    <!-- Tabel Rekap Harian -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden space-y-4 p-6 transition-all duration-300 hover:shadow-md">
        <h3 class="font-bold text-lg text-slate-800">Daftar Transaksi Per Hari</h3>
        
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="bg-transparent py-3">Hari & Tanggal</th>
                        <th class="bg-transparent py-3">Jumlah Transaksi</th>
                        <th class="bg-transparent py-3 text-right">Omzet Harian</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600">
                    @foreach($dailyData as $row)
                        @php
                            $fullDate = \Carbon\Carbon::createFromDate($year, $month, $row->day)->translatedFormat('l, d F Y');
                        @endphp
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="font-semibold text-slate-800 py-4">{{ $fullDate }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium">
                                    {{ $row->total_transaksi }} Transaksi
                                </span>
                            </td>
                            <td class="font-bold text-emerald-600 text-right py-4">Rp {{ number_format($row->total_omzet, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @if($dailyData->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center py-12 text-slate-400 text-xs">Belum ada transaksi pada bulan ini.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Style Animasi Halus -->
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
    function dailyReportApp() {
        return {}
    }

    const rawDaily = @json($dailyData);
    
    // Mendapatkan jumlah hari maksimal pada bulan dan tahun tersebut secara dinamis
    const year = {{ $year }};
    const month = {{ $month }};
    const daysInMonth = new Date(year, month, 0).getDate();

    let daysLabel = [];
    let dailyOmzet = new Array(daysInMonth).fill(0);
    let dailyTransactions = new Array(daysInMonth).fill(0);

    // Mengisi label tanggal dari 1 sampai akhir bulan
    for (let i = 1; i <= daysInMonth; i++) {
        daysLabel.push(i);
    }

    // Memetakan data dari database ke tanggal yang sesuai
    rawDaily.forEach(item => {
        if (item.day >= 1 && item.day <= daysInMonth) {
            dailyOmzet[item.day - 1] = item.total_omzet;
            dailyTransactions[item.day - 1] = item.total_transaksi;
        }
    });

    const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
    
    // Membuat gradasi area chart yang lebih tegas dan jelas bentuk bloknya
    const lineGradient = dailyCtx.createLinearGradient(0, 0, 0, 300);
    lineGradient.addColorStop(0, 'rgba(225, 29, 72, 0.45)'); // Warna awal lebih pekat agar bentuk areanya jelas
    lineGradient.addColorStop(1, 'rgba(225, 29, 72, 0.05)'); // Sisakan sedikit warna tipis di bawah agar menyatu bersih

    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: daysLabel,
            datasets: [{
                label: 'Omzet Harian',
                data: dailyOmzet,
                borderColor: 'rgba(225, 29, 72, 1)',
                backgroundColor: lineGradient,
                borderWidth: 3,
                fill: 'start',       // Memastikan area terisi penuh dari garis ke sumbu bawah
                tension: 0.35,       // Lengkungan garis mulus
                pointBackgroundColor: 'rgba(225, 29, 72, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
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
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal ' + context[0].label + ' {{ $monthName }}';
                        },
                        label: function(context) {
                            let dayIndex = context.dataIndex;
                            let transCount = dailyTransactions[dayIndex];
                            return [
                                ' Omzet: Rp ' + (context.raw || 0).toLocaleString('id-ID'),
                                ' Transaksi: ' + transCount + ' Transaksi'
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
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