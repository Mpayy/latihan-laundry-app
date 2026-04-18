@extends('layouts.app')
@section('title', $title ?? 'Tambah Order')
@section('content')
<div class="row">
    {{-- Kolom Kiri: Form Buat Order --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-cart-plus me-2 text-primary"></i>{{ $title ?? 'Tambah Order' }}
                </h5>

                <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                        <select name="id_customer" class="form-select" id="customerSelect">
                            <option value="">-- Buat Customer Baru --</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" data-is-member="{{ $customer->is_member }}" {{ old('id_customer') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->cutomer_name }} {{ $customer->is_member ? '(Member)' : '' }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_customer')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="newCustomerForm" class="border p-3 rounded mb-3 bg-light">
                        <h6 class="fw-semibold mb-3">Data Customer Baru</h6>
                        <div class="mb-2">
                            <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="newCustomerName">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" id="newCustomerPhone">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" id="newCustomerAddress">{{ old('address') }}</textarea>
                            @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Daftar Layanan <span class="text-danger">*</span></label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="serviceTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Layanan</th>
                                    <th style="width:100px">Qty (Kg)</th>
                                    <th style="width:130px">Subtotal</th>
                                    <th style="width:70px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="id_service[]" class="form-select service" required>
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach ($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                {{ $service->service_name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="qty[]" class="form-control qty" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="subtotal[]" class="form-control subtotal" readonly placeholder="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger removeRow" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-info btn-sm mb-3" id="addRow">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Layanan
                    </button>

                    {{-- [FITUR TAMBAHAN - VOUCHER]
                         Uncomment blok ini jika soal ujian meminta fitur Kode Voucher.
                         Lihat panduan lengkap di OrderController.php --}}
                    {{-- <div class="mb-3 mt-2">
                        <label class="form-label fw-semibold">Kode Voucher (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-ticket-perforated"></i></span>
                            <input type="text" name="voucher_code" id="voucher_code_input" class="form-control" placeholder="Masukkan kode voucher..." value="{{ old('voucher_code') }}">
                            <button class="btn btn-outline-primary" type="button" id="btnCheckVoucher">Cek Voucher</button>
                        </div>
                        <div id="voucherStatus" class="form-text mt-1"></div>
                    </div> --}}
                   

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Order
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Ringkasan Live --}}
    <div class="col-lg-5">
        <div class="card border-primary">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <i class="bi bi-receipt me-2"></i>Ringkasan Pesanan
                </h5>

                <ul class="list-group list-group-flush mb-3" id="summaryList">
                    <li class="list-group-item px-0 text-muted fst-italic" id="emptyMsg">
                        Belum ada layanan dipilih
                    </li>
                </ul>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Subtotal</span>
                        <strong id="summarySubtotal">Rp 0</strong>
                    </li>
                    {{-- [FITUR TAMBAHAN - DISKON] --}}
                         {{-- Uncomment baris ini jika soal ujian meminta fitur Diskon Member/Voucher --}}
                    {{-- <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Diskon (Member/Voucher)</span>
                        <strong class="text-success" id="summaryDiscount">Rp 0</strong>
                    </li> --}}
                    {{-- [FITUR TAMBAHAN - PAJAK] --}}
                         {{-- Uncomment baris ini jika soal ujian meminta fitur Pajak --}}
                    {{-- <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Pajak (10%)</span>
                        <strong id="summaryTax">Rp 0</strong>
                    </li>  --}}
                   
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="fw-bold fs-5">Total Bayar</span>
                        <span class="fw-bold fs-5 text-primary" id="summaryTotal">Rp 0</span>
                    </li>
                </ul>

                <div class="alert alert-info mt-3 mb-0 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Setelah order disimpan, proses pembayaran dapat dilakukan langsung dari halaman ini.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //  const TAX_PERCENT = 10; // [FITUR TAMBAHAN - PAJAK] Uncomment jika diminta fitur pajak

    document.getElementById('customerSelect').addEventListener('change', function() {
        const formObj = document.getElementById('newCustomerForm');
        if (this.value === '') {
            formObj.style.display = 'block';
            document.getElementById('newCustomerName').required = true;
            document.getElementById('newCustomerPhone').required = true;
            document.getElementById('newCustomerAddress').required = true;
        } else {
            formObj.style.display = 'none';
            document.getElementById('newCustomerName').required = false;
            document.getElementById('newCustomerPhone').required = false;
            document.getElementById('newCustomerAddress').required = false;
        }
    });
    // Trigger saat load
    document.getElementById('customerSelect').dispatchEvent(new Event('change'));

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function recalcRow(row) {
        const priceEl = row.querySelector('.service option:checked');
        const price = priceEl ? (parseFloat(priceEl.dataset.price) || 0) : 0;
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const subtotal = price * qty;
        row.querySelector('.subtotal').value = subtotal > 0 ? subtotal : '';
        return subtotal;
    }

    function updateSummary() {
        const rows = document.querySelectorAll('#serviceTable tbody tr');
        let totalSubtotal = 0;
        const summaryList = document.getElementById('summaryList');
        const emptyMsg = document.getElementById('emptyMsg');

        // Hapus item lama kecuali emptyMsg
        summaryList.querySelectorAll('.summary-item').forEach(el => el.remove());

        let hasItem = false;
        rows.forEach(row => {
            const priceEl = row.querySelector('.service option:checked');
            const serviceName = priceEl && priceEl.value ? priceEl.text : null;
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const subtotal = parseFloat(row.querySelector('.subtotal').value) || 0;

            if (serviceName && subtotal > 0) {
                hasItem = true;
                totalSubtotal += subtotal;
                const li = document.createElement('li');
                li.className = 'list-group-item px-0 d-flex justify-content-between align-items-center summary-item';
                li.innerHTML = `
                    <div>
                        <small class="d-block fw-semibold">${serviceName}</small>
                        <small class="text-muted">${qty} Kg</small>
                    </div>
                    <span class="badge bg-info text-dark">${formatRupiah(subtotal)}</span>
                `;
                summaryList.insertBefore(li, emptyMsg);
            }
        });

        emptyMsg.style.display = hasItem ? 'none' : '';

        // Hitung Diskon Member
        // let discountPercent = 0;
        // const customerOpt = document.querySelector('#customerSelect option:checked');
        // if (customerOpt && customerOpt.dataset.isMember == '1') {
        //     discountPercent += 5;
        // }
        
        // Hitung Diskon Voucher
        // const voucherDiscount = parseFloat(document.getElementById('voucher_code_input').dataset.discount || 0);
        // discountPercent += voucherDiscount;

        // const discountAmount = (totalSubtotal * discountPercent) / 100;
        // const totalAfterDiscount = totalSubtotal - discountAmount;

        // Hitung Pajak
        // const tax = (totalAfterDiscount * TAX_PERCENT) / 100;
        // const grandTotal = totalAfterDiscount + tax;

        document.getElementById('summarySubtotal').textContent = formatRupiah(totalSubtotal);
        // [FITUR TAMBAHAN] Uncomment 2 baris ini jika diminta fitur Diskon & Pajak
        // document.getElementById('summaryDiscount').textContent = `- ${formatRupiah(discountAmount)}`;
        // document.getElementById('summaryTax').textContent = formatRupiah(tax);
        document.getElementById('summaryTotal').textContent = formatRupiah(totalSubtotal); // Ganti totalSubtotal -> grandTotal jika aktifkan diskon/pajak
    }

    // Tambah baris
    document.getElementById('addRow').addEventListener('click', function () {
        const tbody = document.querySelector('#serviceTable tbody');
        const firstRow = tbody.rows[0];
        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('select').selectedIndex = 0;
        tbody.appendChild(newRow);
        updateSummary();
    });

    // Event delegasi untuk perubahan di tabel
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('service') || e.target.classList.contains('qty')) {
            const row = e.target.closest('tr');
            recalcRow(row);
            updateSummary();
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty')) {
            const row = e.target.closest('tr');
            recalcRow(row);
            updateSummary();
        }
    });

    // Hapus baris
    document.addEventListener('click', function (e) {
        if (e.target.closest('.removeRow')) {
            const rows = document.querySelectorAll('#serviceTable tbody tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                updateSummary();
            }
        }
    });

    // [FITUR TAMBAHAN - VOUCHER] Uncomment seluruh blok ini jika diminta fitur Cek Voucher
    // document.getElementById('btnCheckVoucher').addEventListener('click', function() {
    //     const code = document.getElementById('voucher_code_input').value;
    //     const statusEl = document.getElementById('voucherStatus');
    //     const inputEl = document.getElementById('voucher_code_input');
    //     if (!code) { statusEl.textContent = ''; inputEl.dataset.discount = 0; updateSummary(); return; }
    //     fetch("{{ route('orders.checkVoucher') }}", {
    //         method: 'POST',
    //         headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    //         body: JSON.stringify({ code: code })
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.valid) {
    //             statusEl.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>${data.message} (Diskon ${data.discount}%)</span>`;
    //             inputEl.dataset.discount = data.discount;
    //         } else {
    //             statusEl.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>${data.message}</span>`;
    //             inputEl.dataset.discount = 0;
    //         }
    //         updateSummary();
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         statusEl.textContent = 'Terjadi kesalahan saat mengecek voucher.';
    //     });
    // });

    // Update summary awal
    updateSummary();
</script>
@endsection