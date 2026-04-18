@extends('layouts.app')
@section('title', $title ?? 'Master Data Order')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bag-check me-2 text-primary"></i>Daftar Order
                        </h5>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Order
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Tanggal Order</th>
                                    <th>Selesai</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $i => $order)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <strong>{{ $order->customer->cutomer_name }}</strong>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                        <td>
                                            {{ $order->order_end_date ? \Carbon\Carbon::parse($order->order_end_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td>Rp {{ number_format($order->total_bayar ?? $order->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if($order->order_status === 0)
                                                <span class="badge bg-danger"><i class="bi bi-clock me-1"></i>Pending</span>
                                            @elseif($order->order_status === 1)
                                                <span class="badge bg-warning text-dark"><i
                                                        class="bi bi-box-seam me-1"></i>Diambil</span>
                                            @elseif($order->order_status === 2)
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                {{-- Tombol Pickup: buka modal konfirmasi --}}
                                                @if($order->order_status === 0)
                                                    <button type="button" class="btn btn-sm btn-success btn-pickup"
                                                        data-order-id="{{ $order->id }}"
                                                        data-customer="{{ $order->customer->cutomer_name }}" data-bs-toggle="modal"
                                                        data-bs-target="#pickupModal">
                                                        <i class="bi bi-box-arrow-up me-1"></i>Pickup
                                                    </button>
                                                @endif

                                                {{-- Tombol Bayar: buka modal bayar --}}
                                                @php
                                                    // Mode dasar: tampilkan total saja tanpa rincian diskon/pajak
                                                    $calcDetails = [
                                                        'baseTotal' => $order->total,
                                                        'grandTotal' => $order->total_bayar ?? $order->total,
                                                        // [FITUR TAMBAHAN - DISKON & PAJAK]
                                                        // Uncomment baris-baris ini jika soal meminta fitur diskon/pajak:
                                                        // 'hasMember'           => $order->customer->is_member ? true : false,
                                                        // 'memberPercent'       => $order->customer->is_member ? 5 : 0,
                                                        // 'memberDiscountAmount'=> ($order->total * ($order->customer->is_member ? 5 : 0)) / 100,
                                                        // 'totalAfterMember'    => $order->total - ($order->total * ($order->customer->is_member ? 5 : 0)) / 100,
                                                        // 'hasVoucher'          => ($order->discount_percent - ($order->customer->is_member ? 5 : 0)) > 0,
                                                        // 'voucherCode'         => $order->voucher->voucher_code ?? 'Voucher',
                                                        // 'voucherPercent'      => max(0, $order->discount_percent - ($order->customer->is_member ? 5 : 0)),
                                                        // 'voucherDiscountAmount'=> ($order->total * max(0, $order->discount_percent - ($order->customer->is_member ? 5 : 0))) / 100,
                                                        // 'totalAfterVoucher'   => $order->total - (($order->total * ($order->customer->is_member ? 5 : 0)) / 100) - (($order->total * max(0, $order->discount_percent - ($order->customer->is_member ? 5 : 0))) / 100),
                                                        // 'taxPercent'          => $order->pajak ?? 0,
                                                        // 'taxAmount'           => $order->jumlah_pajak ?? 0,
                                                    ];

                                                    $orderDetails = $order->details->map(function ($d) {
                                                        return [
                                                            "service" => optional($d->service)->service_name ?? "-",
                                                            "qty" => $d->qty,
                                                            "subtotal" => $d->subtotal,
                                                        ];
                                                    });
                                                @endphp
                                                @if($order->order_status === 1)
                                                    <button type="button" class="btn btn-sm btn-primary btn-bayar"
                                                        data-order-id="{{ $order->id }}"
                                                        data-customer="{{ $order->customer->cutomer_name }}"
                                                        data-total="{{ $order->total_bayar ?? $order->total }}"
                                                        data-calc-details='@json($calcDetails)'
                                                        data-details='@json($orderDetails)' data-bs-toggle="modal"
                                                        data-bs-target="#bayarModal">
                                                        <i class="bi bi-cash-coin me-1"></i>Bayar
                                                    </button>
                                                @endif

                                                @if($order->order_status === 2)
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data order
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL KONFIRMASI PICKUP ===== --}}
    <div class="modal fade" id="pickupModal" tabindex="-1" aria-labelledby="pickupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="pickupModalLabel">
                        <i class="bi bi-box-arrow-up me-2"></i>Konfirmasi Pickup
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">Konfirmasi bahwa laundry untuk customer:</p>
                    <h6 class="fw-bold text-success" id="pickupCustomerName">-</h6>
                    <p class="mb-0 text-muted small">sudah diambil / siap diantar.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="pickupForm" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2 me-1"></i>Ya, Tandai Pickup
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL BAYAR ===== --}}
    <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bayarModalLabel">
                        <i class="bi bi-cash-coin me-2"></i>Proses Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="bayarForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">

                            {{-- Detail Order --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-muted text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing:1px">Detail Pesanan</h6>
                                <ul class="list-group list-group-flush mb-3" id="bayarDetailList">
                                </ul>
                                
                                <h6 class="fw-bold mt-4 mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing:1px">Ringkasan Pembayaran</h6>
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <ul class="list-group list-group-flush bg-transparent" id="bayarRingkasanList">
                                        </ul>
                                    </div>
                                </div>
                            </div>



                            {{-- Form Bayar --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-muted text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing:1px">Pembayaran</h6>

                                <div class="mb-3">
                                    <label class="form-label">Customer</label>
                                    <input type="text" class="form-control bg-light" id="bayarCustomerName" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Total Tagihan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control bg-light fw-bold text-primary"
                                            id="bayarTotalFmt" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="bayarInput" class="form-label fw-semibold">Uang Diterima <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control form-control-lg" id="bayarInput"
                                            name="order_pay" placeholder="0" min="1" required>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Kembalian</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-success">Rp</span>
                                        <input type="text" class="form-control text-success fw-bold" id="kembalianOutput"
                                            name="order_change" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Proses Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ====== PICKUP MODAL ======
        document.querySelectorAll('.btn-pickup').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const orderId = this.dataset.orderId;
                const customer = this.dataset.customer;
                document.getElementById('pickupCustomerName').textContent = customer;
                document.getElementById('pickupForm').action = '/orders/pickup/' + orderId;
            });
        });

        // ====== BAYAR MODAL ======
        document.querySelectorAll('.btn-bayar').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const orderId = this.dataset.orderId;
                const customer = this.dataset.customer;
                const total = parseFloat(this.dataset.total) || 0;
                const details = JSON.parse(this.dataset.details || '[]');
                const calc = JSON.parse(this.dataset.calcDetails || '{}');

                // Set form action
                document.getElementById('bayarForm').action = '/orders/' + orderId + '/bayar';

                // Set nama customer & total
                document.getElementById('bayarCustomerName').value = customer;
                document.getElementById('bayarTotalFmt').value = Number(calc.grandTotal).toLocaleString('id-ID');

                // Reset input
                const bayarInput = document.getElementById('bayarInput');
                const kembalian = document.getElementById('kembalianOutput');
                bayarInput.value = '';
                kembalian.value = '0';

                // Render detail pesanan
                const detailListEl = document.getElementById('bayarDetailList');
                detailListEl.innerHTML = '';

                details.forEach(d => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item px-0 d-flex justify-content-between';
                    li.innerHTML = `
                    <div>
                        <strong>${d.service}</strong>
                        <small class="d-block text-muted">${d.qty} Kg</small>
                    </div>
                    <span class="badge bg-info text-dark align-self-center">
                        Rp ${Number(d.subtotal).toLocaleString('id-ID')}
                    </span>
                `;
                    detailListEl.appendChild(li);
                });

                // Render ringkasan pembayaran (mode dasar: tampilkan total saja)
                const ringkasanListEl = document.getElementById('bayarRingkasanList');
                ringkasanListEl.innerHTML = '';

                // [FITUR TAMBAHAN - DISKON & PAJAK]
                // Uncomment blok ini jika soal meminta fitur diskon/pajak bertahap:
                //
                // // 1. Total Pesanan
                // ringkasanListEl.innerHTML += `<li class="list-group-item px-0 d-flex justify-content-between text-muted border-0 py-1 bg-transparent"><span>Total Pesanan</span><span>Rp ${Number(calc.baseTotal).toLocaleString('id-ID')}</span></li>`;
                // // 2. Diskon Member
                // if (calc.hasMember) {
                //     ringkasanListEl.innerHTML += `<li class="list-group-item px-0 d-flex justify-content-between text-danger border-0 py-1 bg-transparent"><span>Diskon Member (${calc.memberPercent}%)</span><span>- Rp ${Number(calc.memberDiscountAmount).toLocaleString('id-ID')}</span></li>`;
                //     ringkasanListEl.innerHTML += `<li class="list-group-item px-0 d-flex justify-content-between fw-semibold border-0 py-1 bg-transparent"><span style="font-size: 0.85rem">Total setelah diskon member</span><span style="font-size: 0.85rem">Rp ${Number(calc.totalAfterMember).toLocaleString('id-ID')}</span></li>`;
                // }
                // // 3. Voucher
                // if (calc.hasVoucher) {
                //     ringkasanListEl.innerHTML += `<li class="list-group-item px-0 d-flex justify-content-between text-danger border-0 py-1 bg-transparent"><span>Potongan Voucher (${calc.voucherPercent}%)</span><span>- Rp ${Number(calc.voucherDiscountAmount).toLocaleString('id-ID')}</span></li>`;
                //     ringkasanListEl.innerHTML += `<li class="list-group-item px-0 d-flex justify-content-between fw-semibold border-0 py-1 bg-transparent"><span style="font-size: 0.85rem">Total setelah diskon voucher</span><span style="font-size: 0.85rem">Rp ${Number(calc.totalAfterVoucher).toLocaleString('id-ID')}</span></li>`;
                // }
                // // 4. Pajak
                // if (calc.taxPercent > 0) {
                //     ringkasanListEl.innerHTML += `<li class="list-group-item px-0 d-flex justify-content-between border-0 py-1 bg-transparent" style="color: #d9a406 !important;"><span>Pajak PPN (${calc.taxPercent}%)</span><span>+ Rp ${Number(calc.taxAmount).toLocaleString('id-ID')}</span></li>`;
                // }
                // [END FITUR TAMBAHAN]

                // Total Akhir (selalu ditampilkan)
                ringkasanListEl.innerHTML += `
                    <li class="list-group-item px-0 d-flex justify-content-between border-top border-secondary pt-2 mt-2 bg-transparent">
                        <span class="fw-bold">Total Bayar</span>
                        <span class="fw-bold text-primary fs-5">Rp ${Number(calc.grandTotal).toLocaleString('id-ID')}</span>
                    </li>
                `;

                // Hitung kembalian saat input
                bayarInput.oninput = function () {
                    const bayar = parseFloat(this.value) || 0;
                    const sisa = bayar - total;
                    kembalian.value = sisa.toLocaleString('id-ID');
                    kembalian.classList.toggle('text-danger', sisa < 0);
                    kembalian.classList.toggle('text-success', sisa >= 0);
                };
            });
        });
    </script>
@endsection