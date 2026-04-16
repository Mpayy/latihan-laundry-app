@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="row">
        <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-plus me-2 text-primary"></i>{{ $title ?? 'Tambah User' }}
                </h5>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                        <select name="id_level" class="form-select" id="levelSelect" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ old('id_level') == $level->id ? 'selected' : '' }}>
                                {{ $level->level_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Simpan User
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection