@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-primary mb-1"><i class="bi bi-boxes me-2"></i>Manajemen Stok Kerupuk</h1>
            <span class="text-muted">Kelola produk, stok, dan harga dengan mudah</span>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-gradient-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Produk
        </a>
    </div>

    <form method="GET" class="mb-4">
        <div class="input-group w-50">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari produk..." class="form-control rounded-start">
            <button class="btn btn-outline-primary rounded-end" type="submit"><i class="bi bi-search"></i> Cari</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($lowStockProducts->count())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Perhatian:</strong> Ada produk stok rendah:
            <ul class="mb-0">
                @foreach($lowStockProducts as $p)
                <li>{{ $p->name }} <span class="badge bg-danger">{{ $p->stok }}</span></li>
                @endforeach
            </ul>
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
                            <th>Varian</th>
                            <th>Stok</th>
                            <th>Harga Jual</th>
                            <th>HPP</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>{{ $product->varian }}</td>
                            <td>
                                @if($product->stok < 10)
                                    <span class="badge bg-danger">{{ $product->stok }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->stok }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($product->harga_jual,0,',','.') }}</td>
                            <td>
                                <span class="badge bg-gradient-primary text-white">
                                    Rp {{ number_format($product->harga_beli,0,',','.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
    }
    .table thead th {
        border-top: none;
        font-weight: 600;
        letter-spacing: .02em;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f3f9;
    }
</style>
@endsection
