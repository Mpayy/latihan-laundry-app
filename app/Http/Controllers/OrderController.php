<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\LaundryPickup;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        $title = 'Tambah Order';
        $customers = Customer::all();
        $services = Service::all();
        return view('orders.create', compact('customers', 'services', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required',
            'id_service' => 'required|array',
            'qty' => 'required|array',
        ]);

        $order = Order::create([
            'id_customer' => $request->id_customer,
            'order_code' => 'ORD-' . time(),
            'order_date' => now(),
            'order_status' => 0,
            'total' => 0
        ]);

        $total = 0;

        foreach ($request->id_service as $key => $serviceId) {

            $service = Service::find($serviceId);
            $qty = $request->qty[$key];
            $subtotal = $service->price * $qty;

            OrderDetail::create([
                'id_order' => $order->id,
                'id_service' => $serviceId,
                'qty' => $qty,
                'price' => $service->price,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        $taxPercent = 10; // misal 10%
        $taxAmount = ($total * $taxPercent) / 100;
        $grandTotal = $total + $taxAmount;

        $order->update([
            'total' => $total,
            'pajak' => $taxPercent,
            'jumlah_pajak' => $taxAmount,
            'total_bayar' => $grandTotal,
        ]);

        return redirect()->route('orders.index');
    }

    public function index()
    {
        $title = 'Master Data Order';
        $orders = Order::with(['customer', 'details.service'])->latest()->get();
        return view('orders.index', compact('orders', 'title'));
    }

    public function pickup($id)
    {
        $order = Order::find($id);
        // Ubah Status Order dan Update Tanggal Selesai
        $order->update([
            'order_status' => 1,
            'order_end_date' => now(),
        ]);

        // Tambah Data Laundry Pickup
        LaundryPickup::create([
            'id_order' => $order->id,
            'id_customer' => $order->id_customer,
            'pickup_date' => now(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Status pickup berhasil diperbarui.');
    }

    public function bayar($id)
    {
        $order = Order::find($id);
        return view('orders.bayar', compact('order'));
    }

    public function bayarStore(Request $request, $id)
    {
        $request->validate([
            'order_pay' => 'required|numeric|min:1',
        ]);

        $order = Order::find($id);
        $order->update([
            'order_pay'    => $request->order_pay,
            'order_change' => $request->order_pay - ($order->total_bayar ?? $order->total),
            'order_status' => 2,
        ]);

        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil diproses.');
    }
}
