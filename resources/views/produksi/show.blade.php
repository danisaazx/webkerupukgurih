@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5 d-flex align-items-center">
                    <i class="bi bi-clipboard-data me-2"></i> Detail Produksi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-semibold text-muted mb-1">Produk</label>
                        <div class="fs-5 fw-bold text-primary">
                            {{ $produksi->product->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted mb-1">Jumlah Produksi</label>
                            <div class="fs-6">{{ $produksi->jumlah_produksi }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted mb-1">Tanggal Produksi</label>
                            <div class="fs-6">{{ $produksi->tanggal_produksi }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted mb-1">Expired Date</label>
                            <div class="fs-6">{{ $produksi->expired_date ?? '-' }}</div>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3"><i class="bi bi-boxes me-2"></i>Bahan Baku yang Digunakan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Bahan Baku</th>
                                    <th>Jumlah Terpakai</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produksi->details as $detail)
                                <tr>
                                    <td>{{ $detail->bahanBaku->name }}</td>
                                    <td>
                                        <span class="badge bg-gradient-primary text-white">
                                            {{ $detail->qty_terpakai }}
                                        </span>
                                    </td>
                                    <td>{{ $detail->bahanBaku->satuan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('produksi.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
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
    .badge.bg-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        font-size: 1em;
        padding: 0.5em 0.9em;
    }
    .card-header {
        background: #f8fafc;
    }
    .table thead th {
        border-top: none;
        font-weight: 600;
        letter-spacing: .02em;
    }
</style>
@endsection
