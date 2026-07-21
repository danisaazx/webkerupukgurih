@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-primary mb-1"><i class="bi bi-archive me-2"></i>Data Bahan Baku</h1>
            <span class="text-muted">Kelola stok dan harga bahan baku produksi</span>
        </div>
        <a href="{{ route('bahan_baku.create') }}" class="btn btn-gradient-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Bahan Baku
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Total Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bahanBakus as $bahan)
                        <tr>
                            <td class="fw-semibold">{{ $bahan->name }}</td>
                            <td>{{ $bahan->satuan }}</td>
                            <td>
                                @if($bahan->stok < 10)
                                    <span class="badge bg-danger">{{ (int) $bahan->stok }}</span>
                                @else
                                    <span class="badge bg-success">{{ (int) $bahan->stok }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($bahan->harga_beli, 0) }}</td>
                            <td>Rp {{ number_format($bahan->harga_beli * $bahan->stok, 0) }}</td>
                            <td class="text-center">
                                <a href="{{ route('bahan_baku.edit', $bahan->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $bahan->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalHapus{{ $bahan->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Yakin hapus?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Yakin ingin menghapus bahan baku <strong>{{ $bahan->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                <form action="{{ route('bahan_baku.destroy', $bahan->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yakin</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .btn-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        color: #fff !important;
        border: none;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg,#3b82f6 60%,#0d6efd 100%) !important;
        color: #fff !important;
    }
    .badge.bg-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        color: #fff !important;
    }
</style>
@endsection