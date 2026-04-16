@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ $title ?? 'Edit Pelanggan' }}
                    </h5>

                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')


                        <div class="mb-2">
                            <label class="form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" name="cutomer_name" class="form-control" value="{{ $customer->cutomer_name }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ $customer->address }}" required>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i>Edit Pelanggan
                            </button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection