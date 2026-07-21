@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-cart-plus me-2"></i>Tambah Transaksi
                </div>
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Pembeli</label>
                            <input type="text" name="nama_pembeli" class="form-control" placeholder="Contoh: Pelanggan Toko">
                        </div>

                        <label class="form-label fw-semibold">Produk Dibeli</label>
                        <div id="produk-container">
                            <div class="row g-2 align-items-end mb-2 produk-row">
                                <div class="col-7">
                                    <select name="product_id[]" class="form-select" required>
                                        <option value="" disabled selected>Pilih Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->varian }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="number" name="qty[]" class="form-control" placeholder="Jumlah" min="1" value="1" required>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusProduk(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="tambahProduk()">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </button>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-gradient-primary w-100 fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Transaksi
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

<script>
function tambahProduk() {
    const html = `
    <div class="row g-2 align-items-end mb-2 produk-row">
        <div class="col-7">
            <select name="product_id[]" class="form-select" required>
                <option value="" disabled selected>Pilih Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->varian }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <input type="number" name="qty[]" class="form-control" placeholder="Jumlah" min="1" value="1" required>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusProduk(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
    `;
    document.getElementById('produk-container').insertAdjacentHTML('beforeend', html);
}
function hapusProduk(btn) {
    btn.closest('.produk-row').remove();
}
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
    }
    .btn-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        color: #fff !important;
        border: none;
    }
</style>
@endsection