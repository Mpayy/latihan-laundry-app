@extends('layouts.app')
@section('title', $title ?? 'Master Data User')
@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Service</h5>
                    <div class="mb-3" align='right'>
                        <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">Create</a>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Service Name
                                </th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr>
                                <td>{{ $service->service_name }}</td>
                                <td>{{ $service->price }}</td>
                                <td>{{ $service->description }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary">Ubah</a>
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
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