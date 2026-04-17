<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\LaundryPickup;
use Illuminate\Http\Request;
use App\Models\Voucher;

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
            'id_customer' => 'nullable|exists:customers,id',
            'name'        => 'required_without:id_customer|nullable|string|max:255',
            'phone'       => 'required_without:id_customer|nullable|string|max:20',
            'address'     => 'required_without:id_customer|nullable|string',
            'id_service'  => 'required|array',
            'qty'         => 'required|array',
            'voucher_code' => 'nullable|string',
        ]);

        // Tentukan customer: pilih existing atau buat baru sebagai non-member
        if ($request->id_customer) {
            $customer = Customer::findOrFail($request->id_customer);
        } else {
            $customer = Customer::create([
                'cutomer_name' => $request->name,
                'phone'        => $request->phone,
                'address'      => $request->address,
                'is_member'    => false, // Non-member, bisa diubah admin nanti di master data
            ]);
        }

        // Buat order awal
        $order = Order::create([
            'id_customer'  => $customer->id,
            'order_code'   => 'ORD-' . time(),
            'order_date'   => now(),
            'order_status' => 0,
            'total'        => 0,
        ]);

        // Hitung subtotal dari setiap layanan
        $total = 0;
        foreach ($request->id_service as $key => $serviceId) {
            $service  = Service::findOrFail($serviceId);
            $qty      = $request->qty[$key];
            $subtotal = $service->price * $qty;

            OrderDetail::create([
                'id_order'   => $order->id,
                'id_service' => $serviceId,
                'qty'        => $qty,
                'price'      => $service->price,
                'subtotal'   => $subtotal,
            ]);

            $total += $subtotal;
        }

        // --- Kalkulasi Diskon (Backend sebagai sumber kebenaran) ---
        $discountPercent = 0;
        $appliedVoucher  = null;

        // Diskon 5% jika member
        if ($customer->is_member) {
            $discountPercent += 5;
        }

        // Diskon dari voucher (jika ada dan valid)
        $voucherCode = trim($request->voucher_code ?? '');
        if ($voucherCode) {
            $appliedVoucher = Voucher::where('voucher_code', $voucherCode)
                ->where('is_active', 1)
                ->where('expired_at', '>=', now()->startOfDay())
                ->first();

            if ($appliedVoucher) {
                // Tambahkan nilai diskon dari database, bukan hardcode
                $discountPercent += $appliedVoucher->discount_precentage;
            }
        }

        // Hitung nominal diskon, pajak, dan total akhir
        $discountAmount      = ($total * $discountPercent) / 100;
        $totalAfterDiscount  = $total - $discountAmount;
        $taxPercent          = 10;
        $taxAmount           = ($totalAfterDiscount * $taxPercent) / 100;
        $grandTotal          = $totalAfterDiscount + $taxAmount;

        // Simpan semua nilai sebagai snapshot ke tabel order
        $order->update([
            'total'            => $total,
            'discount_percent' => $discountPercent,
            'discount_amount'  => $discountAmount,
            'id_voucher'       => $appliedVoucher?->id,
            'pajak'            => $taxPercent,
            'jumlah_pajak'     => $taxAmount,
            'total_bayar'      => $grandTotal,
        ]);

        // Nonaktifkan voucher setelah dipakai agar tidak bisa digunakan lagi
        if ($appliedVoucher) {
            $appliedVoucher->update(['is_active' => false]);
        }

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat.');
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

    public function checkVoucher(Request $request)
    {
        $voucher = Voucher::where('voucher_code', $request->code)
            ->where('is_active', 1)
            ->where('expired_at', '>=', now()->startOfDay())
            ->first();

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
}
