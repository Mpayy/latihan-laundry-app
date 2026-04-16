@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- ===== KPI CARDS ===== --}}
<div class="row g-3 mb-4">

    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(65,84,241,0.12)">
                    <i class="bi bi-currency-dollar fs-2 text-primary"></i>
                </div>
                <div class="flex-fill">
                    <p class="text-muted small mb-1">Pendapatan Bulan Ini</p>
                    <h5 class="fw-bold mb-0 text-primary">
                        Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                    </h5>
                    {{-- <small class="{{ $pertumbuhanPendapatan >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="bi bi-arrow-{{ $pertumbuhanPendapatan >= 0 ? 'up' : 'down' }}-short"></i>
                        {{ abs($pertumbuhanPendapatan) }}% vs bulan lalu
                    </small> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(46,202,106,0.12)">
                    <i class="bi bi-bag-check fs-2 text-success"></i>
                </div>
                <div class="flex-fill">
                    <p class="text-muted small mb-1">Total Order</p>
                    <h5 class="fw-bold mb-0 text-success">{{ $totalOrder }}</h5>
                    <small class="text-muted">
                        <span class="text-danger">{{ $orderPending }} Pending</span> &bull;
                        <span class="text-warning">{{ $orderDiambil }} Diambil</span> &bull;
                        <span class="text-success">{{ $orderLunas }} Lunas</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(255,119,29,0.12)">
                    <i class="bi bi-people fs-2 text-warning"></i>
                </div>
                <div class="flex-fill">
                    <p class="text-muted small mb-1">Total Customer</p>
                    <h5 class="fw-bold mb-0 text-warning">{{ $totalCustomer }}</h5>
                    <small class="text-muted">Customer terdaftar</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:rgba(13,202,240,0.12)">
                    <i class="bi bi-tags fs-2 text-info"></i>
                </div>
                <div class="flex-fill">
                    <p class="text-muted small mb-1">Jenis Layanan</p>
                    <h5 class="fw-bold mb-0 text-info">{{ $totalLayanan }}</h5>
                    <small class="text-muted">Layanan aktif</small>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== STATUS ORDER (Progress Bar) ===== --}}
{{-- @if($totalOrder > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted fw-semibold mb-3">
                    <i class="bi bi-pie-chart me-2"></i>Status Order Keseluruhan
                </h6>
                <div class="d-flex gap-3 mb-2">
                    <span class="small">
                        <span class="badge bg-danger me-1">{{ $orderPending }}</span>Pending
                        ({{ round($orderPending / $totalOrder * 100) }}%)
                    </span>
                    <span class="small">
                        <span class="badge bg-warning me-1">{{ $orderDiambil }}</span>Diambil
                        ({{ round($orderDiambil / $totalOrder * 100) }}%)
                    </span>
                    <span class="small">
                        <span class="badge bg-success me-1">{{ $orderLunas }}</span>Lunas
                        ({{ round($orderLunas / $totalOrder * 100) }}%)
                    </span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 6px;">
                    @if($orderPending > 0)
                    <div class="progress-bar bg-danger"
                         style="width: {{ ($orderPending / $totalOrder) * 100 }}%"
                         title="Pending: {{ $orderPending }}"></div>
                    @endif
                    @if($orderDiambil > 0)
                    <div class="progress-bar bg-warning"
                         style="width: {{ ($orderDiambil / $totalOrder) * 100 }}%"
                         title="Diambil: {{ $orderDiambil }}"></div>
                    @endif
                    @if($orderLunas > 0)
                    <div class="progress-bar bg-success"
                         style="width: {{ ($orderLunas / $totalOrder) * 100 }}%"
                         title="Lunas: {{ $orderLunas }}"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif --}}

{{-- <div class="row g-3"> --}}

    {{-- ===== GRAFIK PENDAPATAN 6 BULAN ===== --}}
    {{-- <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Tren Pendapatan 6 Bulan Terakhir
                </h5>
                <canvas id="grafikPendapatan" height="100"></canvas>
            </div>
        </div>
    </div> --}}

    {{-- ===== LAYANAN TERPOPULER ===== --}}
    {{-- <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-star me-2 text-warning"></i>Layanan Terpopuler
                </h5>
                @forelse($layananPopuler as $i => $layanan)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="fw-bold text-muted" style="width:22px;font-size:14px;">#{{ $i + 1 }}</div>
                    <div class="flex-fill">
                        <p class="mb-1 fw-semibold small">{{ $layanan->service_name }}</p>
                        <div class="progress" style="height:6px;">
                            @php
                                $maxQty = $layananPopuler->max('total_qty');
                                $pct = $maxQty > 0 ? round(($layanan->total_qty / $maxQty) * 100) : 0;
                                $colors = ['bg-primary','bg-success','bg-warning','bg-info','bg-secondary'];
                            @endphp
                            <div class="progress-bar {{ $colors[$i] ?? 'bg-primary' }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    <div class="text-end" style="min-width:55px;">
                        <small class="fw-bold">{{ $layanan->total_qty }} kg</small><br>
                        <small class="text-muted" style="font-size:9px;">Rp {{ number_format($layanan->total_pendapatan, 0, ',', '.') }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data
                </div>
                @endforelse
            </div>
        </div>
    </div> --}}

    {{-- ===== ORDER TERBARU ===== --}}
    {{-- <div class="col-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2 text-info"></i>Order Terbaru
                    </h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Kode Order</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orderTerbaru as $order)
                            <tr>
                                <td><strong>{{ $order->customer->cutomer_name }}</strong></td>
                                <td><code>{{ $order->order_code }}</code></td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td>Rp {{ number_format($order->total_bayar ?? $order->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->order_status === 0)
                                        <span class="badge bg-danger"><i class="bi bi-clock me-1"></i>Pending</span>
                                    @elseif($order->order_status === 1)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-box-seam me-1"></i>Diambil</span>
                                    @elseif($order->order_status === 2)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada order
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

{{-- </div> --}}

{{-- Chart.js Script --}}
{{-- <script>
    const ctx = document.getElementById('grafikPendapatan').getContext('2d');
    const grafikLabels = @json(array_column($grafikData, 'label'));
    const grafikNilai  = @json(array_column($grafikData, 'nilai'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grafikLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikNilai,
                backgroundColor: 'rgba(65, 84, 241, 0.15)',
                borderColor: '#4154f1',
                borderWidth: 2,
                borderRadius: 6,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return 'Rp ' + ctx.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            if (val >= 1000000) return 'Rp ' + (val/1000000).toFixed(1) + 'jt';
                            if (val >= 1000) return 'Rp ' + (val/1000).toFixed(0) + 'rb';
                            return 'Rp ' + val;
                        },
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
</script> --}}
@endsection