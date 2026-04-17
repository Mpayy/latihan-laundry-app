@extends('layouts.app')
@section('title', $title ?? 'Master Data User')
@section('content')
<section class="section">
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people-fill me-2 text-primary"></i>Daftar Customer
                    </h5>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Customer
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Customer</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $i => $customer)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $customer->cutomer_name }}</strong>
                                </td>
                                <td>{{ $customer->phone }}</td>
                                <td><span class="badge bg-primary">{{ $customer->address }}</span></td>
                                <td>
                                    @if($customer->is_member)
                                        <span class="badge bg-success">Member</span>
                                    @else
                                        <span class="badge bg-secondary">Non-Member</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
                                                <i class="bi bi-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data customer
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
</section>
@endsection