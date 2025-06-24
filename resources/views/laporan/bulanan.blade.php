@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-3">
        <i class="bi bi-graph-up-arrow me-2"></i>Laporan Bulanan
    </h2>

    <form method="GET" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold mb-1">Pilih Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="form-control shadow-sm">
        </div>
        <div class="col-md-6 d-flex gap-2">
            <button class="btn btn-gradient-primary shadow-sm">
                <i class="bi bi-search"></i> Tampilkan
            </button>
            <a href="{{ route('laporan.bulanan.excel', ['bulan' => $bulan]) }}" class="btn btn-success shadow-sm">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('laporan.bulanan.pdf', ['bulan' => $bulan]) }}" class="btn btn-danger shadow-sm">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="fs-3 mb-2"><i class="bi bi-cash-coin"></i></div>
                    <div class="fw-semibold">Total Penjualan</div>
                    <div class="fs-5 fw-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="fs-3 mb-2"><i class="bi bi-graph-up"></i></div>
                    <div class="fw-semibold">Total Profit</div>
                    <div class="fs-5 fw-bold">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="fs-3 mb-2"><i class="bi bi-box-seam"></i></div>
                    <div class="fw-semibold">Total Produk Terjual</div>
                    <div class="fs-5 fw-bold">{{ $totalQty }} </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-primary">Tanggal</th>
                            <th class="text-primary">Nama Produk</th>
                            <th class="text-primary">Qty</th>
                            <th class="text-primary">Harga Satuan</th>
                            <th class="text-primary">Subtotal</th>
                            <th class="text-primary">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $t)
                        @foreach($t->details as $d)
                            @php
                                $hpp = $d->product->harga_beli ?? 0;
                                $profit = ($d->harga_satuan - $hpp) * $d->qty;
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-gradient-secondary px-3 py-2">
                                        {{ \Carbon\Carbon::parse($d->tanggal_penjualan)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $d->product->name }}</span>
                                    @if($d->product->varian)
                                        <span class="badge bg-info text-white ms-1">{{ $d->product->varian }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-gradient-primary text-white">{{ $d->qty }}</span>
                                </td>
                                <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($d->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="fw-semibold text-success">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg,#06b6d4 60%,#3b82f6 100%) !important;
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg,#f59e42 60%,#fbbf24 100%) !important;
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg,#64748b 60%,#94a3b8 100%) !important;
        color: #fff !important;
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
