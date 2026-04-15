@extends('layouts.app')
@section('content')
    <div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail Pesanan</h5>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Customer</span>
                        <strong>{{ $order->customer->cutomer_name }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Layanan</span>
                        <span class="badge bg-info text-dark">{{ $order->details->first()->service->service_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Harga /Kg</span>
                        <strong>Rp {{ number_format($order->details->first()->service->price, 0, ',', '.') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Berat</span>
                        <strong>{{ $order->details->first()->qty }} Kg</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="h5 mb-0">Total Tagihan</span>
                        <span class="h5 mb-0 text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Proses Pembayaran</h5>

                <form action="{{ route('orders.bayarStore', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" id="total" value="{{ $order->total }}">

                    <div class="mb-3">
                        <label for="order_pay" class="form-label text-muted small">Uang yang Diterima</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control form-control-lg" id="bayar" name="order_pay" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="order_change" class="form-label text-muted small">Kembalian</label>
                        <div class="input-group">
                            <span class="input-group-text text-success">Rp</span>
                            <input type="text" class="form-control form-control-lg text-success fw-bold" id="kembalian" name="order_change" value="0" readonly>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Bayar</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const Bayar = document.getElementById('bayar');
    const Kembalian = document.getElementById('kembalian');
    const Total = document.getElementById('total').value;

    Bayar.addEventListener('input', function() {
        const perhitungan = Bayar.value - Total;

        Kembalian.value = perhitungan;
    });
</script>
@endsection