@extends('layouts.app')
@section('content')
<div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags me-2 text-primary"></i>{{ $title ?? 'Edit Service' }}
                    </h5>

                    <form action="{{ route('services.update', $service->id) }}" method="POST">
                        @csrf
                        @method('PUT')


                        <div class="mb-2">
                            <label class="form-label fw-semibold">Nama Service <span class="text-danger">*</span></label>
                            <input type="text" name="service_name" class="form-control" value="{{ $service->service_name }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ $service->price }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control" value="{{ $service->description }}" required>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i>Edit Service
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