<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end   = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $orders = Order::with(['customer', 'details.service'])
            ->where('order_status', 2)
            ->whereBetween('order_date', [$start, $end])
            ->orderBy('order_date', 'desc')
            ->get();

        // Ringkasan keuangan
        $totalPendapatan  = $orders->sum('total_bayar');
        $totalSubtotal    = $orders->sum('total');
        $totalPajak       = $orders->sum('jumlah_pajak');
        $jumlahTransaksi  = $orders->count();

        // Pendapatan per layanan
        $pendapatanPerLayanan = [];
        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $name = $detail->service->service_name ?? 'Unknown';
                if (!isset($pendapatanPerLayanan[$name])) {
                    $pendapatanPerLayanan[$name] = ['qty' => 0, 'pendapatan' => 0];
                }
                $pendapatanPerLayanan[$name]['qty']        += $detail->qty;
                $pendapatanPerLayanan[$name]['pendapatan'] += $detail->subtotal;
            }
        }
        arsort($pendapatanPerLayanan);

        return view('reports.index', compact(
            'orders', 'start', 'end',
            'totalPendapatan', 'totalSubtotal', 'totalPajak',
            'jumlahTransaksi', 'pendapatanPerLayanan'
        ));
    }

    public function exportPdf(Request $request)
    {
        $start = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end   = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $orders = Order::with(['customer', 'details.service'])
            ->where('order_status', 2)
            ->whereBetween('order_date', [$start, $end])
            ->orderBy('order_date', 'desc')
            ->get();

        $totalPendapatan = $orders->sum('total_bayar');
        $totalSubtotal   = $orders->sum('total');
        $totalPajak      = $orders->sum('jumlah_pajak');
        $jumlahTransaksi = $orders->count();

        $pendapatanPerLayanan = [];
        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $name = $detail->service->service_name ?? 'Unknown';
                if (!isset($pendapatanPerLayanan[$name])) {
                    $pendapatanPerLayanan[$name] = ['qty' => 0, 'pendapatan' => 0];
                }
                $pendapatanPerLayanan[$name]['qty']        += $detail->qty;
                $pendapatanPerLayanan[$name]['pendapatan'] += $detail->subtotal;
            }
        }

        $pdf = Pdf::loadView('reports.pdf', compact(
            'orders', 'start', 'end',
            'totalPendapatan', 'totalSubtotal', 'totalPajak',
            'jumlahTransaksi', 'pendapatanPerLayanan'
        ))->setPaper('a4', 'portrait');

        $filename = 'laporan-laundry-' . $start . '-sd-' . $end . '.pdf';

        return $pdf->download($filename);
    }
}
