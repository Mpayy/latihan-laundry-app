<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $customer = $this->resolveCustomer($data);

            $order = Order::create([
                "id_customer" => $customer->id,
                "order_code" => "ORD-".time(),
                "order_date" => now(),
                "order_status" => 0,
                "total" => 0
            ]);

            $services = Service::whereIn("id", $data["id_service"])->get()->keyBy("id");

            $total = 0;
            $orderDetails = [];

            foreach($data["id_service"] as $key => $serviceId){
                $service = $services->get($serviceId);
                if(!$service) continue;

                $qty = $data["qty"][$key];
                $subtotal = $service->price * $qty;
                $orderDetails[] = [
                    "id_service" =>$serviceId,
                    "qty" => $qty,
                    "price"=> $service->price,
                    "subtotal"=> $subtotal,
                    "created_at" => now(),
                    "updated_at"=> now()
                ];

                $total += $subtotal;
            }

            $order->details()->createMany($orderDetails);

            $discountPercent = $customer->is_member ? 5 : 0;
            $appliedVoucher = null;

            if(!empty($data["voucher_code"])){
                $appliedVoucher = Voucher::where("voucher_code", trim($data["voucher_code"]))
                ->where("is_active", true)
                ->whereDate("expired_at",">=", now())
                ->first();

                if ($appliedVoucher) { 
                    $discountPercent += $appliedVoucher->discount_precentage; 
                    $appliedVoucher->update(['is_active' => false]);
                }
            }

            $taxPercent     = 10; 
            $taxAmount      = ($total * $taxPercent) / 100;
            $totalWithTax   = $total + $taxAmount;
            $discountAmount = ($totalWithTax * $discountPercent) / 100;
            $grandTotal     = $totalWithTax - $discountAmount;

            $orderUpdateData = [
                'total'            => $total,
                'discount_percent' => $discountPercent,
                'discount_amount'  => $discountAmount,
                'id_voucher'       => $appliedVoucher?->id,
                'pajak'            => $taxPercent,
                'jumlah_pajak'     => $taxAmount,
                'total_bayar'      => $grandTotal,
            ];

            $orderPay = $data['order_pay'] ?? 0;
            if (($data['payment_method'] ?? '') === 'now' && $orderPay >= $grandTotal) {
                $orderUpdateData['order_pay']    = $orderPay;
                $orderUpdateData['order_change'] = $orderPay - $grandTotal;
                $orderUpdateData['order_status'] = 3; // Lunas
            }

            $order->update($orderUpdateData);

            return $order;
        });
    }

    private function resolveCustomer(array $data): Customer
    {
        if(!empty($data["id_customer"])) {
            return Customer::findOrFail($data["id_customer"]);
        }

        return Customer::create([
            'cutomer_name' => $data['name'], // disesuaikan dengan typo di modelmu: 'cutomer_name'
            'phone'        => $data['phone'],
            'address'      => $data['address'],
            'is_member'    => false,
        ]);
    
    }

    public function processPayment(Order $order, float $amount): void
    {
    $totalTagihan = $order->total_bayar ?? $order->total;

    if ($amount < $totalTagihan) {
        // Lempar exception jika uang kurang (akan ditangkap Laravel sebagai eror validasi)
        throw \Illuminate\Validation\ValidationException::withMessages([
            'order_pay' => ['Uang yang dibayarkan tidak cukup!'],
        ]);
    }

    $order->update([
        'order_pay'    => $amount,
        'order_change' => $amount - $totalTagihan,
        'order_status' => 2,
    ]);
    }
}