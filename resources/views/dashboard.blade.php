@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center gap-3">
            <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%);">
                <i class="bi bi-speedometer2 text-white fs-3"></i>
            </div>
            <div>
                <h1 class="mb-0 fw-bold text-primary">Dashboard</h1>
                <small class="text-muted">Ringkasan stok & transaksi terbaru</small>
            </div>
        </div>
    </div>

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

    <div class="row g-4">
        <!-- Kiri: Chart Penjualan & Profit + Chart Stok Produk -->
        <div class="col-12 col-lg-7">
            <div class="d-flex flex-column gap-4 h-100">
                <!-- Chart Penjualan & Profit (Circle) -->
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white border-0 pb-0 d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                            <i class="bi bi-pie-chart-fill text-success"></i>
                        </div>
                        <span class="fw-semibold fs-5">Penjualan & Profit Bulan Ini</span>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height:220px;">
                        <canvas id="circleChart" height="180"></canvas>
                    </div>
                </div>
                <!-- Chart Stok Produk -->
                <div class="card border-0 shadow-lg flex-grow-1">
                    <div class="card-header bg-white border-0 pb-0 d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                            <i class="bi bi-bar-chart-fill text-primary"></i>
                        </div>
                        <span class="fw-semibold fs-5">Stok Produk</span>
                    </div>
                    <div class="card-body">
                        <canvas id="stokChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kanan: Riwayat Transaksi Terbaru -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 pb-0 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                        <i class="bi bi-clock-history text-warning"></i>
                    </div>
                    <span class="fw-semibold fs-5">Riwayat Transaksi Terbaru</span>
                </div>
                <div class="card-body p-0" style="height: 480px;">
                    <div class="table-responsive" style="max-height: 100%; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pembeli</th>
                                    <th>Produk</th>
                                    <th>Keuntungan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $trx)
                                @php
                                    $profit = 0;
                                    $totalProduct = 0;
                                @endphp
                                @foreach($trx->details as $d)
                                    @php
                                        $hpp = $d->product->harga_beli ?? 0;
                                        $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                                        $profit += $produkProfit;
                                        $totalProduct++;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $trx->nama_pembeli }}</span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-gradient-primary text-white px-3 py-2" style="background:linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%);">
                                            {{ $totalProduct }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">Rp {{ number_format($profit,0,',','.') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('d/m/Y') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada transaksi.</td>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk chart penjualan & profit (circle)
    const totalPenjualan = {{ $totalPenjualan ?? 0 }};
    const totalProfit = {{ $totalProfit ?? 0 }};
    const circleChart = new Chart(document.getElementById('circleChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Penjualan', 'Profit'],
            datasets: [{
                data: [totalPenjualan, totalProfit],
                backgroundColor: [
                    'rgba(13, 110, 253, 0.85)',
                    'rgba(34, 197, 94, 0.85)'
                ],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed;
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + value.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Chart stok produk (bar)
    const ctx = document.getElementById('stokChart').getContext('2d');
    const stokChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($products->pluck('name')),
            datasets: [{
                label: 'Stok',
                data: @json($products->pluck('stok')),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.85)',
                    'rgba(59, 130, 246, 0.85)',
                    'rgba(13, 110, 253, 0.85)',
                    'rgba(0, 123, 255, 0.85)',
                    'rgba(0, 172, 193, 0.85)'
                ],
                borderRadius: 12,
                maxBarThickness: 40
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<!-- Bootstrap Icons (jika belum ada di layout) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
    }
    .card-header {
        background: #f8fafc;
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