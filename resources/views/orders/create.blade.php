@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $title ?? '' }}</h5>
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Customer*</label>
                        <select name="id_customer" class="form-control">
                            <option value="">--Select Customer--</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->cutomer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <table class="table" id="serviceTable">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Qty (Kg)</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="id_service[]" class="form-control service">
                                        <option value="">--Select Service--</option>
                                        @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                            {{ $service->service_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control qty" value="1">
                                </td>
                                <td>
                                    <input type="text" name="subtotal[]" class="form-control subtotal" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger removeRow">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-info mb-3" id="addRow">Tambah Service</button>

                    <br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addRow').addEventListener('click', function() {
        let table = document.querySelector('#serviceTable tbody');
        let row = table.rows[0].cloneNode(true);

        row.querySelectorAll('input').forEach(input => input.value = '');
        row.querySelector('.subtotal').value = '';

        table.appendChild(row);
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('service') || e.target.classList.contains('qty')) {
            let row = e.target.closest('tr');
            let price = row.querySelector('.service option:checked').dataset.price || 0;
            let qty = row.querySelector('.qty').value || 0;
            row.querySelector('.subtotal').value = price * qty;
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            let rows = document.querySelectorAll('#serviceTable tbody tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            }
        }
    });
</script>
@endsection