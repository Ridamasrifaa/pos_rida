<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Harian - {{ $monthName }} {{ $year }}</title>
    <style>
        /* CSS untuk menghilangkan header/footer bawaan browser saat print (seperti URL dan Judul Tab) */
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
            font-size: 13px;
            margin: 0;
            padding: 25px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #0f172a;
            padding-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header p {
            margin: 6px 0 0;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
            page-break-inside: auto; 
        }
        tr {
            page-break-inside: avoid; 
            page-break-after: auto;
        }
        thead {
            display: table-header-group; 
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 9px 12px;
            text-align: left;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .trx-header {
            background-color: #e2e8f0;
            font-weight: bold;
            color: #0f172a;
            font-size: 12.5px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .no-print {
            margin-bottom: 20px;
            text-align: right;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            background: #0f172a;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        .btn:hover { background: #1e293b; }
        
        .back-link {
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #f8fafc;
        }
        .back-link:hover { background: #f1f5f9; color: #0f172a; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; color: #000000; }
            th { background-color: #0f172a !important; color: #ffffff !important; -webkit-print-color-adjust: exact; }
            .trx-header { background-color: #e2e8f0 !important; -webkit-print-color-adjust: exact; }
            tfoot td { background-color: #cbd5e1 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button class="btn" onclick="window.print()">Cetak / Simpan ke PDF</button>
        <a href="{{ route('admin.reports.daily', ['year' => $year, 'month' => $month]) }}" class="back-link">Kembali</a>
    </div>

    <div class="header">
        <h2>Laporan Rincian Transaksi Harian</h2>
        <p>Periode: <strong>{{ $monthName }} {{ $year }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 45%;">Nama Barang</th>
                <th style="width: 15%; text-align: center;">Jumlah</th>
                <th style="width: 20%; text-align: right;">Harga Satuan</th>
                <th style="width: 20%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
                @php 
                    $formattedDate = \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i');
                @endphp

                <tr class="trx-header">
                    <td colspan="4">
                        Nota: <strong>#{{ $trx->id }}</strong> &nbsp;|&nbsp; 
                        <span style="color: #334155; font-weight: normal;">Waktu: {{ $formattedDate }}</span> &nbsp;|&nbsp; 
                        Total Bayar: <span style="color: #047857; font-weight: 700;">Rp {{ number_format($trx->total_pembayaran, 0, ',', '.') }}</span>
                    </td>
                </tr>

                @foreach($trx->itemPenjualans as $detail)
                    <tr>
                        <td style="padding-left: 20px; color: #0f172a;">- {{ $detail->produk->nama ?? 'Produk' }}</td>
                        <td class="text-center">{{ $detail->kuantitas ?? $detail->jumlah ?? 1 }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right font-bold" style="color: #0f172a;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach

            @if($transactions->isEmpty())
                <tr>
                    <td colspan="4" class="text-center" style="padding: 25px; color: #475569; font-style: italic;">Belum ada data transaksi pada bulan ini.</td>
                </tr>
            @endif
        </tbody>
        @if($transactions->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="3" class="text-right font-bold" style="background-color: #cbd5e1; color: #0f172a; font-size: 13px;">TOTAL KESELURUHAN OMZET:</td>
                <td class="text-right font-bold" style="background-color: #cbd5e1; color: #047857; font-size: 14px;">Rp {{ number_format($transactions->sum('total_pembayaran'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

</body>
</html>