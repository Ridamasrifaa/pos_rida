@extends('layouts.app')

@section('title', 'Rincian Harian - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fade-in">

    <!-- Header Halaman & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Rincian Harian: {{ $monthName }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Daftar transaksi dan rincian barang selama bulan ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Tombol Download Rekap -->
           <a href="{{ route('admin.reports.daily.download', ['year' => $year, 'month' => $month]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
    Cetak / Download PDF
</a>
            <a href="{{ route('admin.reports.monthly', ['year' => $year]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                Kembali
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

    <!-- Tabel Master-Detail Transaksi -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden space-y-4 p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="font-bold text-lg text-slate-800">Daftar Nota Transaksi</h3>
            <span class="text-xs text-slate-400">Klik tombol "Detail" pada baris untuk melihat rincian barang</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="bg-transparent py-3">ID / Waktu</th>
                        <th class="bg-transparent py-3">Jumlah Jenis Item</th>
                        <th class="bg-transparent py-3">Total Pembayaran</th>
                        <th class="bg-transparent py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600">
                    @foreach($transactions as $trx)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="py-4">
                                <span class="font-semibold text-slate-800">#{{ $trx->id }}</span>
                                <div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i') }}</div>
                            </td>
                            <td class="py-4 text-slate-600 font-medium">
                                {{ $trx->itemPenjualans->count() }} Item Barang
                            </td>
                            <td class="py-4 font-bold text-emerald-600">
                                Rp {{ number_format($trx->total_pembayaran, 0, ',', '.') }}
                            </td>
                            <td class="py-4 text-center">
                                <button type="button" onclick="toggleDetail({{ $trx->id }})" id="btn-{{ $trx->id }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                    <span id="txt-{{ $trx->id }}">Detail</span>
                                    <svg id="icon-{{ $trx->id }}" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </td>
                        </tr>

                        <!-- Baris Rincian Item (Hidden by Default) -->
                        <tr id="detail-{{ $trx->id }}" class="hidden bg-slate-50/80 border-b border-slate-100">
                            <td colspan="4" class="p-4">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm space-y-3">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Barang Nota #{{ $trx->id }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs text-slate-600">
                                            <thead>
                                                <tr class="border-b border-slate-100 text-slate-400">
                                                    <th class="py-2 text-left">Nama Barang</th>
                                                    <th class="py-2 text-center">Qty</th>
                                                    <th class="py-2 text-right">Harga Satuan</th>
                                                    <th class="py-2 text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($trx->itemPenjualans as $detail)
                                                    <tr class="border-b border-slate-50 last:border-0">
                                                        <td class="py-2 font-medium text-slate-800">{{ $detail->produk->nama ?? 'Produk' }}</td>
                                                        <td class="py-2 text-center">
                                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md font-medium">
                                                                {{ $detail->kuantitas ?? $detail->jumlah ?? 1 }}
                                                            </span>
                                                        </td>
                                                        <td class="py-2 text-right text-slate-500">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                                        <td class="py-2 text-right font-bold text-emerald-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($transactions->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-400 text-xs">Belum ada transaksi pada bulan ini.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Style Animasi -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<!-- CDN Chart.js & Vanilla JS Toggle -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleDetail(id) {
        const detailRow = document.getElementById('detail-' + id);
        const textSpan = document.getElementById('txt-' + id);
        const iconSvg = document.getElementById('icon-' + id);

        if (detailRow.classList.contains('hidden')) {
            detailRow.classList.remove('hidden');
            textSpan.innerText = 'Tutup';
            iconSvg.classList.add('rotate-180');
        } else {
            detailRow.classList.add('hidden');
            textSpan.innerText = 'Detail';
            iconSvg.classList.remove('rotate-180');
        }
    }

    const rawDaily = @json($dailyData);
    
    // Mendapatkan jumlah hari maksimal pada bulan dan tahun tersebut secara dinamis
    const year = {{ $year }};
    const month = {{ $month }};
    const daysInMonth = new Date(year, month, 0).getDate();

    let daysLabel = [];
    let dailyOmzet = new Array(daysInMonth).fill(0);
    let dailyTransactions = new Array(daysInMonth).fill(0);

    for (let i = 1; i <= daysInMonth; i++) {
        daysLabel.push(i);
    }

    rawDaily.forEach(item => {
        if (item.day >= 1 && item.day <= daysInMonth) {
            dailyOmzet[item.day - 1] = item.total_omzet;
            dailyTransactions[item.day - 1] = item.total_transaksi;
        }
    });

    const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
    
    const lineGradient = dailyCtx.createLinearGradient(0, 0, 0, 300);
    lineGradient.addColorStop(0, 'rgba(225, 29, 72, 0.45)'); 
    lineGradient.addColorStop(1, 'rgba(225, 29, 72, 0.05)'); 

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
                fill: 'start',      
                tension: 0.35,      
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