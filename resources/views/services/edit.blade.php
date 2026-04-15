@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $title ?? '' }}</h5>
                <form action="{{ route('services.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Service Name*</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" value="{{ $service->service_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price*</label>
                        <input type="text" class="form-control" id="price" name="price" value="{{ $service->price }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description*</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ $service->description }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection