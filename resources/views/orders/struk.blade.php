<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Laundry - {{ $order->order_code }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; font-family: 'Courier New', Courier, monospace; font-size: 13px; background: #f0f0f0; }
        .struk-container { width: 80mm; background: #fff; margin: 20px auto; padding: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-2 { margin-top: 10px; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { vertical-align: top; padding: 2px 0; }
        .no-print { text-align: center; margin-bottom: 20px; padding-top: 20px; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; margin: 0 5px; font-weight: bold; font-family: sans-serif; }
        .btn-print { background: #007bff; }
        .btn-close { background: #6c757d; }
        @media print {
            body { background: #fff; }
            .struk-container { margin: 0; width: 100%; box-shadow: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">Cetak Struk</button>
        <button class="btn btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="struk-container">
        <div class="text-center mb-2">
            <h3 style="margin:0; font-size: 18px;">LAUNDRY APP</h3>
            <p style="margin:0; font-size: 12px;">Pusat Pelatihan Kerja Daerah</p>
            <p style="margin:0; font-size: 12px;">Telp: 0812-3456-7890</p>
        </div>
        
        <div class="divider"></div>
        
        <table>
            <tr><td>No Order</td><td>:</td><td>{{ $order->order_code }}</td></tr>
            <tr><td>Tanggal</td><td>:</td><td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Customer</td><td>:</td><td>{{ $order->customer->cutomer_name }}</td></tr>
            <tr>
                <td>Status Bayar</td>
                <td>:</td>
                <td class="font-bold">
                    @if($order->order_status == 2 || $order->order_status == 3)
                        LUNAS
                    @else
                        BELUM LUNAS
                    @endif
                </td>
            </tr>
        </table>
        
        <div class="divider"></div>
        
        <table>
            @foreach($order->details as $item)
            <tr>
                <td colspan="3" class="font-bold">{{ $item->service->service_name }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} Kg x Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
        
        <div class="divider"></div>
        
        <table>
            <!-- Jika Mode Lengkap (Diskon/Pajak) diaktifkan, ini akan muncul -->
            @if($order->jumlah_pajak > 0)
            <tr>
                <td>Subtotal</td><td>:</td><td class="text-right">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak ({{ $order->pajak }}%)</td><td>:</td><td class="text-right">Rp{{ number_format($order->jumlah_pajak, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($order->discount_amount > 0)
            <tr>
                <td>Diskon</td><td>:</td><td class="text-right">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            <tr style="font-size: 14px;">
                <td class="font-bold mt-2">TOTAL BAYAR</td>
                <td class="font-bold mt-2">:</td>
                <td class="text-right font-bold mt-2">Rp{{ number_format($order->total_bayar ?? $order->total, 0, ',', '.') }}</td>
            </tr>
            
            @if($order->order_status == 2 || $order->order_status == 3)
            <tr>
                <td>Tunai</td><td>:</td><td class="text-right">Rp{{ number_format($order->order_pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td><td>:</td><td class="text-right">Rp{{ number_format($order->order_change, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>
        
        <div class="divider"></div>
        
        <div class="text-center">
            <p style="margin:0; font-size: 12px;" class="font-bold">Terima Kasih</p>
            <p style="margin:5px 0 0 0; font-size: 10px;">Harap periksa kembali cucian Anda sebelum meninggalkan tempat.</p>
        </div>
    </div>
</body>
</html>
