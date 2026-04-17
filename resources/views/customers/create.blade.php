@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="row">
        <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-plus me-2 text-primary"></i>{{ $title ?? 'Tambah Customer' }}
                </h5>

                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Nama Customer <span class="text-danger">*</span></label>
                        <input type="text" name="cutomer_name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="is_member" class="form-select" required>
                            <option value="1">Member</option>
                            <option value="0">Non-Member</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Customer
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