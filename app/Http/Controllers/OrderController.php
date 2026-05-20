<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\LaundryPickup;
use App\Models\Order;
use App\Models\Service;
use App\Models\Voucher;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        $title = 'Tambah Order';
        $customers = Customer::where("is_member", 1)->get();
        $services = Service::all();
        return view('orders.create', compact('customers', 'services', 'title'));
    }

    protected $orderService;

    // Inject OrderService melalui constructor
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request)
    {
        // Ambil data yang sudah lolos validasi
        $validatedData = $request->validated();

        // Lempar eksekusi logika ke Service
        $this->orderService->createOrder($validatedData);

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat.');
    }

    public function index()
    {
        $title = 'Master Data Order';
        $orders = Order::with(['customer', 'details.service'])->latest()->get();
        return view('orders.index', compact('orders', 'title'));
    }

    public function pickup(Order $order)
    {
        $order->update([
            'order_status' => $order->order_status === 3 ? 2 : 1,
            'order_end_date' => now(),
        ]);

        LaundryPickup::create([
            'id_order' => $order->id,
            'id_customer' => $order->id_customer,
            'pickup_date' => now(),
        ]);
        return redirect()->route('orders.index')->with('success', 'Status pickup berhasil diperbarui.');
    }

    public function bayar(Order $order)
    {
        return view('orders.bayar', compact('order'));
    }


    public function bayarStore(Request $request, Order $order)
    {
        $request->validate(['order_pay' => 'required|numeric']);
        $this->orderService->processPayment($order, $request->order_pay);
        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil diproses.');
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        $voucher = Voucher::active(trim($request->code))->first();

        if ($voucher) {
            return response()->json([
                'valid' => true,
                'discount' => $voucher->discount_precentage,
                'message' => 'Voucher berhasil digunakan!'
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Voucher tidak ditemukan atau sudah tidak berlaku.'
        ]);
    }

    public function cetakStruk(Order $order)
    {
        $order->load(['customer', 'details.service']);
        return view('orders.struk', compact('order'));
    }
}
