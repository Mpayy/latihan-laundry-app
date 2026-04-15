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
        $customers = Customer::all();
        $services = Service::all();
        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required',
            'id_service' => 'required',
            'qty' => 'required|numeric|min:1',
        ]);

        $service = Service::find($request->id_service);
        $subtotal = $service->price * $request->qty;
        $order = Order::create([
            'id_customer' => $request->id_customer,
            'order_code' => 'ORD-'.time(),
            'order_date' => now(),
            'order_status' => 0,
            'total' => 0
        ]);

        OrderDetail::create([
            'id_order' => $order->id,
            'id_service' => $service->id,
            'qty' => $request->qty,
            'price' => $service->price,
            'subtotal' => $subtotal,
        ]);

        $order->update([
            'total' => $subtotal
        ]);

        return redirect()->route('orders.index');
    }

    public function index()
    {
        $title = 'Data Order';
        $orders = Order::with(['customer'])->latest()->get();
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

        return redirect()->route('orders.index');
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
            'order_pay' => $request->order_pay,
            'order_change' => $request->order_pay - $order->total,
            'order_status' => 2,
        ]);

        return redirect()->route('orders.index');
    }
    
}
