@extends('layouts.app')
@section('title', $title ?? 'Master Data User')
@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data User</h5>
                    <div class="mb-3" align='right'>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Create</a>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Nama
                                </th>
                                <th>Email</th>
                                <th>Level</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->level->level_name }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Ubah</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
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