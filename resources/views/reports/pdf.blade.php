<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pendapatan Laundry</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            background: #fff;
        }

        /* Header */
        .header {
            background: #4154f1;
            color: white;
            padding: 18px 24px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 10px; opacity: 0.85; margin-top: 3px; }
        .header .periode {
            font-size: 11px;
            background: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            margin-top: 6px;
        }

        /* KPI Cards */
        .kpi-row {
            display: table;
            width: 100%;
            margin-bottom: 18px;
            border-collapse: separate;
            border-spacing: 6px;
        }
        .kpi-cell {
            display: table-cell;
            width: 25%;
            background: #f8f9fa;
            border-left: 4px solid #4154f1;
            padding: 10px 12px;
            vertical-align: top;
        }
        .kpi-cell.green  { border-left-color: #2eca6a; }
        .kpi-cell.orange { border-left-color: #ff771d; }
        .kpi-cell.teal   { border-left-color: #0dcaf0; }
        .kpi-label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .kpi-value { font-size: 13px; font-weight: bold; color: #333; margin-top: 4px; }

        /* Sections */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #4154f1;
            padding: 6px 0;
            border-bottom: 2px solid #4154f1;
            margin-bottom: 10px;
        }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead th {
            background: #4154f1;
            color: white;
            padding: 7px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        thead th.text-right { text-align: right; }
        tbody tr:nth-child(even) { background: #f8f9ff; }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            font-size: 9px;
            vertical-align: top;
        }
        tbody td.text-right { text-align: right; }
        tfoot td {
            background: #e8ecff;
            padding: 7px 8px;
            font-weight: bold;
            font-size: 10px;
            color: #4154f1;
        }
        tfoot td.text-right { text-align: right; }

        /* Layanan summary */
        .layanan-table { width: 100%; border-collapse: collapse; }
        .layanan-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #eee;
            font-size: 9px;
        }
        .layanan-table td.nilai { text-align: right; font-weight: bold; color: #2eca6a; }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #999;
            text-align: center;
        }

        .two-col { display: table; width: 100%; }
        .col-left  { display: table-cell; width: 60%; vertical-align: top; padding-right: 12px; }
        .col-right { display: table-cell; width: 40%; vertical-align: top; }

        .badge-service {
            display: inline-block;
            background: #e8ecff;
            color: #4154f1;
            border-radius: 3px;
            padding: 1px 5px;
            font-size: 8px;
            margin: 1px 1px 1px 0;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>&#128736; Laporan Pendapatan Laundry</h1>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
        <span class="periode">Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</span>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-row">
        <div class="kpi-cell">
            <div class="kpi-label">Total Pendapatan</div>
            <div class="kpi-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-cell green">
            <div class="kpi-label">Jumlah Transaksi</div>
            <div class="kpi-value">{{ $jumlahTransaksi }} Order</div>
        </div>
        <div class="kpi-cell orange">
            <div class="kpi-label">Total Pajak (10%)</div>
            <div class="kpi-value">Rp {{ number_format($totalPajak, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-cell teal">
            <div class="kpi-label">Net (Sebelum Pajak)</div>
            <div class="kpi-value">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="two-col">
        <!-- Tabel Transaksi -->
        <div class="col-left">
            <div class="section-title">Detail Transaksi</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:20px">#</th>
                        <th style="width:62px">Tanggal</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th class="text-right" style="width:75px">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                        <td>{{ $order->customer->cutomer_name }}</td>
                        <td>
                            @foreach($order->details as $d)
                                <span class="badge-service">{{ $d->service->service_name ?? '-' }} {{ $d->qty }}kg</span>
                            @endforeach
                        </td>
                        <td class="text-right">Rp&nbsp;{{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#999;padding:20px;">
                            Tidak ada transaksi di periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($orders->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">TOTAL PENDAPATAN</td>
                        <td class="text-right">Rp&nbsp;{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Ringkasan per Layanan -->
        <div class="col-right">
            <div class="section-title">Pendapatan per Layanan</div>
            <table class="layanan-table">
                <thead>
                    <tr>
                        <th style="background:#2eca6a;color:white;padding:6px 8px;font-size:9px;text-align:left;">Layanan</th>
                        <th style="background:#2eca6a;color:white;padding:6px 8px;font-size:9px;text-align:center;width:30px;">Qty</th>
                        <th style="background:#2eca6a;color:white;padding:6px 8px;font-size:9px;text-align:right;">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendapatanPerLayanan as $nama => $data)
                    <tr>
                        <td>{{ $nama }}</td>
                        <td style="text-align:center;">{{ $data['qty'] }} kg</td>
                        <td class="nilai">Rp&nbsp;{{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#999;padding:12px;">-</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan ini digenerate otomatis oleh Sistem Laundry &bull; Periode {{ \Carbon\Carbon::parse($start)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
    </div>

</body>
</html>
