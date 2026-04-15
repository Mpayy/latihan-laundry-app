@extends('layouts.app')
@section('title', $title ?? 'Tambah User')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $title ?? '' }}</h5>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id_customer" class="form-label">Customer*</label>
                            <select name="id_customer" id="id_customer" class="form-control">
                                <option value="">--Select Customer--</option>
                                @foreach ( $customers as $customer )
                                    <option value="{{ $customer->id }}">{{ $customer->cutomer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_service" class="form-label">Service*</label>
                            <select name="id_service" id="id_service" class="form-control">
                                <option value="">--Select Service--</option>
                                @foreach ( $services as $service )
                                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="qty" class="form-label">Berat (Kg)*</label>
                            <input type="number" class="form-control" id="qty" name="qty" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="subtotal" class="form-label">Subtotal*</label>
                            <input type="number" class="form-control" id="subtotal" name="subtotal" readonly>
                        </div> --}}
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection