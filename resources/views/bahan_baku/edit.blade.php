@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-pencil-square me-2"></i>Edit Bahan Baku
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('bahan_baku.update', $bahanBaku->id) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ $bahanBaku->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="{{ $bahanBaku->satuan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Stok</label>
                            <input type="number" step="0.01" name="stok" class="form-control" value="{{ $bahanBaku->stok }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Beli</label>
                            <input type="number" step="0.01" name="harga_beli" class="form-control" value="{{ $bahanBaku->harga_beli }}" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-gradient-primary px-4 fw-semibold">
                                <i class="bi bi-save me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (jika belum ada di layout) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
    }
    .btn-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        color: #fff !important;
        border: none;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg,#0b5ed7 60%,#3b82f6 100%) !important;
    }
</style>
@endsection
