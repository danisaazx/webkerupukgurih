

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-3">
        <i class="bi bi-graph-up-arrow me-2"></i>Laporan Penjualan
    </h2>

    <form method="GET" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold mb-1">Pilih Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="form-control shadow-sm">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold mb-1">Atau Pilih Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal ?? '' }}" class="form-control shadow-sm">
        </div>
        <div class="col-md-6 d-flex gap-2">
            <button class="btn btn-gradient-primary shadow-sm">
                <i class="bi bi-search"></i> Tampilkan
            </button>
            <a href="{{ route('laporan.bulanan.excel', ['bulan' => $bulan, 'tanggal' => $tanggal]) }}" class="btn btn-success shadow-sm"
                onclick="window.location.href=this.href+'&ngrok-skip-browser-warning=true'; return false;">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('laporan.bulanan.pdf', ['bulan' => $bulan, 'tanggal' => $tanggal]) }}" class="btn btn-danger shadow-sm"
                onclick="window.location.href=this.href+'&ngrok-skip-browser-warning=true'; return false;">
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

    {{-- TAB NAVIGATION --}}
    <ul class="nav nav-tabs mb-3" id="laporanTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#tab-transaksi" type="button" role="tab" aria-controls="tab-transaksi" aria-selected="true">
                <i class="bi bi-receipt me-1"></i>Transaksi
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="stok-tab" data-bs-toggle="tab" data-bs-target="#tab-stok" type="button" role="tab" aria-controls="tab-stok" aria-selected="false">
                <i class="bi bi-box-seam me-1"></i>Stok Produk
            </button>
        </li>
    </ul>

    <div class="tab-content" id="laporanTabContent">

        {{-- TAB 1: TRANSAKSI --}}
        <div class="tab-pane fade show active" id="tab-transaksi" role="tabpanel" aria-labelledby="transaksi-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-primary">Tanggal</th>
                                    <th class="text-primary">Nama Produk</th>
                                    <th class="text-primary">Qty</th>
                                    <th class="text-primary">HPP</th>
                                    <th class="text-primary">Subtotal</th>
                                    <th class="text-primary">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $t)
                               @foreach($t->details as $d)
                                    @if($d->product)
                                    @php
                                        $hpp = $d->product->hpp ?? 0;
                                        $profit = ($d->harga_satuan - $hpp) * $d->qty;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-gradient-secondary px-3 py-2">
                                                {{ \Carbon\Carbon::parse($t->tanggal_pembelian)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $d->product->name }}</span>
                                            @if($d->product->varian)
                                                @if ($d->product->varian == 'Pedas' )
                                                <span class="badge bg-danger text-white ms-1">{{ $d->product->varian }}</span>
                                                @elseif ($d->product->varian == "Original")
                                                <span class="badge bg-success text-white ms-1">{{ $d->product->varian }}</span>                                            
                                                @elseif ($d->product->varian == "Asin")
                                                <span class="badge bg-warning text-white ms-1">{{ $d->product->varian }}</span>
                                                @else
                                                <span class="badge bg-info text-white ms-1">{{ $d->product->varian }}</span>                               
                                            @endif             
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-gradient-primary text-white">{{ $d->qty }}</span>
                                        </td>
                                        <td>Rp {{ number_format($d->product->hpp, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($d->qty * $d->harga_satuan, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="fw-semibold text-success">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi pada bulan ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: STOK PRODUK --}}
        <div class="tab-pane fade" id="tab-stok" role="tabpanel" aria-labelledby="stok-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-primary">Nama Produk</th>
                                    <th class="text-primary">Varian</th>
                                    <th class="text-primary">Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produkStok as $p)
                                <tr>
                                    <td class="fw-semibold">{{ $p->name }}</td>
                                    <td>
                                        @if($p->varian == 'Pedas')
                                        <span class="badge bg-danger text-white">{{ $p->varian }}</span>
                                        @elseif($p->varian == 'Original')
                                        <span class="badge bg-success text-white">{{ $p->varian }}</span>
                                        @elseif($p->varian == 'Asin')
                                        <span class="badge bg-warning text-white">{{ $p->varian }}</span>
                                        @else
                                        <span class="badge bg-info text-white">{{ $p->varian }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->stok < 10)
                                        <span class="badge bg-danger">{{ $p->stok }}</span>
                                        @else
                                        <span class="badge bg-success">{{ $p->stok }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data produk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
    .nav-tabs .nav-link {
        color: #64748b;
        border: none;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd !important;
        background: transparent;
        border-bottom: 3px solid #0d6efd;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #0d6efd;
    }
</style>
@endsection
