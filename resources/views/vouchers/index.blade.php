@extends('layouts.app')
@section('title', $title ?? 'Data Voucher')
@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3 mb-3" align='right'>
                        <a href="{{ route('vouchers.create') }}" class="btn btn-primary">Tambah</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Voucher</th>
                                <th>Potongan (%)</th>
                                <th>Batas Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $index => $voucher)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $voucher->voucher_code }}</td>
                                <td>{{ $voucher->discount_precentage }}%</td>
                                <td>{{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($voucher->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-primary btn-sm">Ubah</a>
                                    <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
