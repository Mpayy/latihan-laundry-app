@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="row">
        <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-tags me-2 text-primary"></i>{{ $title ?? 'Tambah Layanan' }}
                </h5>

                <form action="{{ route('services.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="service_name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Layanan
                        </button>
                        <a href="{{ route('services.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection