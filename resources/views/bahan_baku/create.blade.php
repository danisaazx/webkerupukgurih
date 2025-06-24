@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Bahan Baku
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('bahan_baku.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nama bahan baku">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="satuan" class="form-control" placeholder="kg, liter, pack" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Stok</label>
                            <input type="number" step="0.01" name="stok" class="form-control" required placeholder="Jumlah stok">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Beli</label>
                            <input type="number" step="0.01" name="harga_beli" class="form-control" required placeholder="Harga beli per satuan">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-gradient-primary px-4 fw-semibold">
                                <i class="bi bi-save me-1"></i> Simpan
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
        background: linear-gradient(135deg,#3b82f6 60%,#0d6efd 100%) !important;
        color: #fff !important;
    }
</style>
@endsection
