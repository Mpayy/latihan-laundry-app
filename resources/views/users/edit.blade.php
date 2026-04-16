@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>{{ $title ?? 'Edit User' }}
                </h5>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                        <select name="id_level" class="form-select" id="levelSelect" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ $level->id == $user->id_level ? 'selected' : '' }}>
                                {{ $level->level_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                        <span class="text-secondary">Kosongkan kolom ini jika Anda tidak ingin mengubah kata sandi.</span>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Edit User
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection