<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        // ── Statistik Utama ──────────────────────────────────────────────────
        $totalOrder       = Order::count();
        $orderPending     = Order::where('order_status', 0)->count();
        $orderDiambil     = Order::where('order_status', 1)->count();
        $orderLunas       = Order::where('order_status', 2)->count();
        $totalCustomer    = Customer::count();
        $totalLayanan     = Service::count();

        // Total pendapatan bulan ini (order lunas)
        $bulanIni = Carbon::now()->format('Y-m');
        $pendapatanBulanIni = Order::where('order_status', 2)
            ->whereRaw("DATE_FORMAT(order_date, '%Y-%m') = ?", [$bulanIni])
            ->sum('total_bayar');

        // // Total pendapatan bulan lalu
        // $bulanLalu = Carbon::now()->subMonth()->format('Y-m');
        // $pendapatanBulanLalu = Order::where('order_status', 2)
        //     ->whereRaw("DATE_FORMAT(order_date, '%Y-%m') = ?", [$bulanLalu])
        //     ->sum('total_bayar');

        // // Persentase pertumbuhan
        // $pertumbuhanPendapatan = $pendapatanBulanLalu > 0
        //     ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
        //     : ($pendapatanBulanIni > 0 ? 100 : 0);

        // // ── Order Terbaru (5 terakhir) ───────────────────────────────────────
        // $orderTerbaru = Order::with('customer')
        //     ->latest()
        //     ->take(5)
        //     ->get();

        // // ── Grafik pendapatan 6 bulan terakhir ──────────────────────────────
        // $grafikData = [];
        // for ($i = 5; $i >= 0; $i--) {
        //     $bulan = Carbon::now()->subMonths($i);
        //     $label = $bulan->isoFormat('MMM YYYY');
        //     $nilai = Order::where('order_status', 2)
        //         ->whereRaw("DATE_FORMAT(order_date, '%Y-%m') = ?", [$bulan->format('Y-m')])
        //         ->sum('total_bayar');
        //     $grafikData[] = ['label' => $label, 'nilai' => $nilai];
        // }

        // // ── Layanan Terpopuler (top 5) ───────────────────────────────────────
        // $layananPopuler = \DB::table('trans_order_details as od')
        //     ->join('type_of_services as s', 's.id', '=', 'od.id_service')
        //     ->join('trans_orders as o', 'o.id', '=', 'od.id_order')
        //     ->where('o.order_status', 2)
        //     ->selectRaw('s.service_name, SUM(od.qty) as total_qty, SUM(od.subtotal) as total_pendapatan')
        //     ->groupBy('s.id', 's.service_name')
        //     ->orderByDesc('total_qty')
        //     ->take(5)
        //     ->get();

        return view('dashboard', compact(
            'title',
            'totalOrder', 'orderPending', 'orderDiambil', 'orderLunas',
            'totalCustomer', 'totalLayanan',
            'pendapatanBulanIni', 
            // 'pendapatanBulanLalu', 'pertumbuhanPendapatan',
            // 'orderTerbaru', 'grafikData', 'layananPopuler'
        ));
    }
}
