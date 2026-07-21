@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1"><i class="bi bi-clipboard-data me-2"></i>Daftar Produksi</h2>
            <span class="text-muted">Riwayat proses produksi produk</span>
        </div>
        <a href="{{ route('produksi.create') }}" class="btn btn-gradient-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Produksi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Expired</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produksis as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->product->name }}</td>
                            <td>
                                <span class="badge bg-gradient-primary text-white">
                                    {{ $item->jumlah_produksi }}
                                </span>
                            </td>
                            <td>{{ $item->tanggal_produksi }}</td>
                            <td>
                                @if($item->expired_date)
                                    @php $status = $item->status_expired; @endphp
                                    <span class="badge bg-{{ $status['color'] === 'red' ? 'danger' : ($status['color'] === 'orange' ? 'warning text-dark' : 'success') }}">
                                        {{ \Carbon\Carbon::parse($item->expired_date)->format('Y-m-d') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        @if($status['status'] === 'expired')
                                            Lewat {{ $status['days'] }} hari
                                        @elseif($status['status'] === 'warning')
                                            {{ $status['days'] }} hari lagi
                                        @endif
                                    </small>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('produksi.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @if($produksis->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data produksi.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (jika belum ada di layout) -->
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
    }
    .table thead th {
        border-top: none;
        font-weight: 600;
        letter-spacing: .02em;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(13,110,253,.075) !important;
    }
</style>
@endsection
