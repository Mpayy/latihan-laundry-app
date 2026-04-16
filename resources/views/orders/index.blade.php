@extends('layouts.app')
@section('title', $title ?? 'Master Data User')
@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3 mb-3" align='right'>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary">Tambah</a>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Customer
                                </th>
                                <th>Tanggal Order</th>
                                <th>Tanggal Akhir Pesanan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ambil</th>
                                <th>Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->customer->cutomer_name }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->order_end_date }}</td>
                                <td>{{ $order->total }}</td>
                                <td>
                                    @if($order->order_status === 0)
                                        <span class="badge bg-danger">Pending</span>
                                    @elseif($order->order_status === 1)
                                        <span class="badge bg-warning">Diambil</span>
                                    @elseif($order->order_status === 2)
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->order_status === 0)
                                        <form action="{{ route('orders.pickup', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-success">Pickup</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    @if($order->order_status === 1)
                                        <a href="{{ route('orders.bayar', $order->id) }}" class="btn btn-primary">Bayar</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                </div>
            </div>

        </div>
    </div>
</section>
@endsection