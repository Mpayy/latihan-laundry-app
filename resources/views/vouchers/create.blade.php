@extends('layouts.app')
@section('title', $title ?? 'Tambah Voucher')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $title }}</h5>
                <form action="{{ route('vouchers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode Voucher</label>
                        <input type="text" name="voucher_code" class="form-control" value="{{ old('voucher_code') }}" required>
                        @error('voucher_code') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Potongan (%)</label>
                        <input type="number" name="discount_precentage" class="form-control" value="{{ old('discount_precentage') }}" min="1" max="100" required>
                        @error('discount_precentage') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batas Waktu Kadaluarsa</label>
                        <input type="datetime-local" name="expired_at" class="form-control" value="{{ old('expired_at') }}" required>
                        @error('expired_at') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
