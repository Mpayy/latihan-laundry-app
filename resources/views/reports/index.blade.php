@extends('layouts.app')
@section('title', 'Laporan Pendapatan')
@section('content')

{{-- Filter Bar --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $start }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $end }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.exportPdf', ['start_date' => $start, 'end_date' => $end]) }}"
                           class="btn btn-danger flex-fill" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                    <i class="bi bi-cash-stack fs-3 text-primary"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Total Pendapatan</p>
                    <h5 class="fw-bold mb-0 text-primary">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10">
                    <i class="bi bi-receipt fs-3 text-success"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Jumlah Transaksi</p>
                    <h5 class="fw-bold mb-0 text-success">{{ $jumlahTransaksi }} Order</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-percent fs-3 text-warning"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Total Pajak</p>
                    <h5 class="fw-bold mb-0 text-warning">Rp {{ number_format($totalPajak, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-10">
                    <i class="bi bi-box-seam fs-3 text-info"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Net (Sebelum Pajak)</p>
                    <h5 class="fw-bold mb-0 text-info">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Tabel Detail Transaksi --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-table me-2 text-primary"></i>Detail Transaksi Lunas
                    <span class="badge bg-primary ms-2">{{ $start }} s/d {{ $end }}</span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Layanan</th>
                                <th class="text-end">Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $i => $order)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</small>
                                </td>
                                <td><strong>{{ $order->customer->cutomer_name }}</strong></td>
                                <td>
                                    @foreach($order->details as $d)
                                        <span class="badge bg-light text-dark border me-1">
                                            {{ $d->service->service_name ?? '-' }} ({{ $d->qty }}kg)
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Tidak ada transaksi di periode ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($orders->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="fw-bold text-end">TOTAL PENDAPATAN</td>
                                <td class="text-end fw-bold fs-6 text-primary">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan per Layanan --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-bar-chart-line me-2 text-success"></i>Pendapatan per Layanan
                </h5>
                @if(count($pendapatanPerLayanan) > 0)
                    @php $maxPendapatan = max(array_column($pendapatanPerLayanan, 'pendapatan')); @endphp
                    @foreach($pendapatanPerLayanan as $namaLayanan => $data)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold">{{ $namaLayanan }}</span>
                            <span class="small text-muted">{{ $data['qty'] }} kg</span>
                        </div>
                        <div class="progress" style="height:8px;">
                            @php
                                $persen = $maxPendapatan > 0 ? round(($data['pendapatan'] / $maxPendapatan) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                        </div>
                        <div class="text-end">
                            <small class="text-success fw-bold">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Tidak ada data layanan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
