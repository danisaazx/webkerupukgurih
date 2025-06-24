@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1"><i class="bi bi-receipt me-2"></i>Riwayat Transaksi</h2>
            <span class="text-muted">Daftar seluruh transaksi penjualan produk</span>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-gradient-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Transaksi
        </a>
    </div>

    <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 align-items-end mb-4">
        <div class="col-md-2">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-gradient-primary"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pembeli</th>
                            <th>Jumlah Produk</th>
                            <th>Qty</th>
                            <th>Total Harga</th>
                            <th>Profit</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaction as $t)

                        @php
                        $profit = 0;
                        $qty= 0;
                        $total_harga = 0;
                        @endphp

                        @foreach($t->details as $d)
                        @php
                                $hpp = $d->product->harga_beli ?? 0;
                                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                                $profit += $produkProfit;
                                $qty += $d->qty;
                                $total_harga += $d->total_harga;
                                @endphp
                        @endforeach
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_penjualan)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                                <td>{{ $t->nama_pembeli }}</td>
                                <td>{{ $t->details->count() }} Produk</td>
                                <td>
                                    <span class="badge bg-gradient-primary text-white">
                                        {{ $qty }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="fw-semibold text-success">
                                        Rp {{ number_format($profit, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $t->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $transaction->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Transaksi --}}
@foreach($transaction as $t)
<div class="modal fade" id="modalDetail{{ $t->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $t->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalDetailLabel{{ $t->id }}">
                    <i class="bi bi-receipt me-2"></i>Detail Transaksi #{{ $t->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nama Pembeli:</strong> {{ $t->nama_pembeli ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($t->tanggal_penjualan)->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga Beli</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $profit = 0;
                            @endphp
                            @foreach ($t->details as $d)
                            @php
                                $hpp = $d->product->harga_beli ?? 0;
                                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                                $profit += $produkProfit;
                            @endphp
                            <tr>
                                <td>{{ $d->product->name }} | {{$d->product->varian}}</td>
                                <td>{{ $d->qty }}</td>
                                <td>Rp {{ number_format($hpp, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($d->total_harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($produkProfit, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <strong>Total Profit: </strong>
                    <span class="text-success fw-bold">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

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
        background-color: #f1f3f9;
    }
    .pagination {
        --bs-pagination-active-bg: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%);
        --bs-pagination-active-border-color: transparent;
    }
    .pagination .page-item .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        color: #0d6efd;
        border: none;
        transition: background 0.2s, color 0.2s;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        color: #fff !important;
        border: none;
        box-shadow: 0 2px 8px rgba(13,110,253,0.08);
    }
    .pagination .page-item .page-link:hover {
        background: #f1f3f9;
        color: #0d6efd;
    }
    .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 4px 32px rgba(0,0,0,0.08);
    }
    .modal-header {
        border-bottom: none;
    }
</style>
@endsection
