@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-gear-fill me-2"></i>Produksi Baru
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('produksi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="product_id" class="form-label fw-semibold">Produk</label>
                            <select name="product_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} @if($product->varian) - {{ $product->varian }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Produksi</label>
                            <input type="number" name="jumlah_produksi" class="form-control" min="1" required placeholder="Masukkan jumlah produksi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Produksi</label>
                            <input type="date" name="tanggal_produksi" class="form-control" required>
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
