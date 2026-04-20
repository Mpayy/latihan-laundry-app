<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\LaundryPickup;
use Illuminate\Http\Request;
// --- [START FITUR TAMBAHAN: DISKON VOUCHER] ---
// Uncomment baris ini jika diminta fitur Voucher
// use App\Models\Voucher;
// --- [END FITUR TAMBAHAN: DISKON VOUCHER] ---

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
            // --- [START FITUR TAMBAHAN: DISKON VOUCHER] ---
            // Uncomment baris ini jika diminta validasi fitur Voucher
            // 'voucher_code' => 'nullable|string',
            // --- [END FITUR TAMBAHAN: DISKON VOUCHER] ---
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

        // Siapkan variabel default jika fitur tidak diaktifkan
        $discountPercent = 0;
        $discountAmount  = 0;
        $taxPercent      = 0;
        $taxAmount       = 0;
        $appliedVoucher  = null;

        // --- [START FITUR TAMBAHAN: DISKON MEMBER] ---
        // Jika diminta fitur diskon otomatis bagi member, hilangkan tanda // di awal baris bawah ini:
        // if ($customer->is_member) { $discountPercent += 5; } // Diskon 5% untuk member
        // --- [END FITUR TAMBAHAN: DISKON MEMBER] ---

        // --- [START FITUR TAMBAHAN: DISKON VOUCHER] ---
        // Jika diminta fitur diskon dengan kode voucher, hilangkan tanda /* dan */ di bawah ini:
        /*
        $voucherCode = trim($request->voucher_code ?? '');
        if ($voucherCode) {
            $appliedVoucher = \App\Models\Voucher::where('voucher_code', $voucherCode)
                ->where('is_active', 1)->where('expired_at', '>=', now()->startOfDay())->first();
            if ($appliedVoucher) { $discountPercent += $appliedVoucher->discount_precentage; }
        }
        */
        // --- [END FITUR TAMBAHAN: DISKON VOUCHER] ---

        // --- [START FITUR TAMBAHAN: PAJAK PPN] ---
        // Jika diminta fitur pajak (misal 10%), hilangkan tanda // di awal baris bawah ini:
        // $taxPercent = 10;
        // --- [END FITUR TAMBAHAN: PAJAK PPN] ---

        // ==========================================
        // PERHITUNGAN AKHIR (Biarkan blok ini selalu aktif)
        // Jika fitur di atas dikomentari, % diskon dan pajak tetap bernilai 0.
        $taxAmount      = ($total * $taxPercent) / 100;
        $totalWithTax   = $total + $taxAmount;
        $discountAmount = ($totalWithTax * $discountPercent) / 100;
        $grandTotal     = $totalWithTax - $discountAmount;

        $order->update([
            'total'            => $total,
            'discount_percent' => $discountPercent,
            'discount_amount'  => $discountAmount,
            'id_voucher'       => $appliedVoucher?->id,
            'pajak'            => $taxPercent,
            'jumlah_pajak'     => $taxAmount,
            'total_bayar'      => $grandTotal,
        ]);

        if ($appliedVoucher) { $appliedVoucher->update(['is_active' => false]); }
        // ==========================================

        // Handle Pembayaran di Muka
        if ($request->payment_method === 'now' && $request->order_pay >= $grandTotal) {
            $order->update([
                'order_pay'    => $request->order_pay,
                'order_change' => $request->order_pay - $grandTotal,
                'order_status' => 3, // Status khusus: Pending & Lunas
            ]);
        }

        // Mode dasar (tanpa diskon & pajak) - comment/hapus 2 baris ini jika mengaktifkan blok atas
        // $order->update(['total' => $total, 'total_bayar' => $total]);

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
            'order_status' => $order->order_status == 3 ? 2 : 1,
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

    // --- [START FITUR TAMBAHAN: DISKON VOUCHER] ---
    /*
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
    */
    // --- [END FITUR TAMBAHAN: DISKON VOUCHER] ---

    // --- [START FITUR TAMBAHAN: CETAK STRUK] ---
    /*
    public function cetakStruk($id)
    {
        $order = Order::with(['customer', 'details.service'])->findOrFail($id);
        return view('orders.struk', compact('order'));
    }
    */
    // --- [END FITUR TAMBAHAN: CETAK STRUK] ---
}
