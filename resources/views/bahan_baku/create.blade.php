@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Bahan Baku
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('bahan_baku.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nama bahan baku">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Satuan</label>
                            <select name="satuan" class="form-select" required>
                                <option value="" disabled selected>Pilih Satuan</option>
                                <option value="kg">kg</option>
                                <option value="liter">liter</option>
                                <option value="ml">ml</option>
                                <option value="pack">pack</option>
                                <option value="pcs">pcs</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Stok</label>
                            <input type="number" step="0.01" name="stok" id="stok" class="form-control" required placeholder="Jumlah stok">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Beli per Satuan</label>
                            <input type="number" step="0.01" name="harga_beli" id="harga_beli" class="form-control" required placeholder="Harga beli per satuan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Total Pembelian</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="total_beli" class="form-control fw-bold text-success" readonly value="0">
                            </div>
                            <small class="text-muted">Harga beli × stok</small>
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .bg-gradient-primary { background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important; }
    .btn-gradient-primary { background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important; color: #fff !important; border: none; }
    .btn-gradient-primary:hover { background: linear-gradient(135deg,#3b82f6 60%,#0d6efd 100%) !important; }
</style>

<script>
function hitungTotal() {
    const stok = parseFloat(document.getElementById('stok').value) || 0;
    const harga = parseFloat(document.getElementById('harga_beli').value) || 0;
    const total = stok * harga;
    document.getElementById('total_beli').value = Math.round(total).toLocaleString('id-ID');
}

document.getElementById('stok').addEventListener('input', hitungTotal);
document.getElementById('harga_beli').addEventListener('input', hitungTotal);
</script>
@endsection