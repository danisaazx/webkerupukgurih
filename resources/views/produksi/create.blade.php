@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-gear-fill me-2"></i>Produksi Baru
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('produksi.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Produk</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-resep="{{ json_encode($product->recipes->map(function($r) {
                                            return [
                                                'nama' => $r->bahanBaku->name ?? '-',
                                                'qty' => $r->qty_per_batch,
                                                'satuan' => $r->satuan,
                                                'harga' => $r->bahanBaku->harga_beli ?? 0,
                                            ];
                                        })) }}">
                                        {{ $product->name }} @if($product->varian) - {{ $product->varian }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Info Resep -->
                        <div id="info-resep" class="mb-3 d-none">
                            <label class="form-label fw-semibold">Resep Bahan Baku</label>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bahan</th>
                                        <th>Qty/batch</th>
                                        <th>Satuan</th>
                                        <th>Harga/kg</th>
                                    </tr>
                                </thead>
                                <tbody id="resep-tbody"></tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Produksi (pack)</label>
                            <input type="number" name="jumlah_produksi" id="jumlah_produksi" class="form-control" min="1" required placeholder="Masukkan jumlah produksi">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Produksi</label>
                            <input type="date" name="tanggal_produksi" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>

                        <hr>
                        <label class="form-label fw-semibold">Biaya Operasional</label>
                        <div class="mb-3">
                            <label class="form-label">Biaya Gas (Rp)</label>
                            <input type="number" name="biaya_gas" id="biaya_gas" class="form-control" placeholder="0" value="0" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biaya Bensin (Rp)</label>
                            <input type="number" name="biaya_bensin" id="biaya_bensin" class="form-control" placeholder="0" value="0" min="0">
                        </div>

                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estimasi HPP per Pack</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="hpp-display" class="form-control" readonly value="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Markup Keuntungan (%)</label>
                            <input type="number" name="markup" id="markup" class="form-control" placeholder="30" value="30" min="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Jual Otomatis</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="harga-jual-display" class="form-control fw-bold text-success" readonly value="0">
                            </div>
                            <small class="text-muted">HPP × (1 + markup%) — akan tersimpan ke produk</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-gradient-primary px-4 fw-semibold">
                                <i class="bi bi-save me-1"></i> Simpan & Update Harga
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
let resepData = [];

document.getElementById('product_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    resepData = JSON.parse(selected.getAttribute('data-resep') || '[]');

    const tbody = document.getElementById('resep-tbody');
    tbody.innerHTML = '';

    if (resepData.length > 0) {
        document.getElementById('info-resep').classList.remove('d-none');
        resepData.forEach(r => {
            tbody.innerHTML += `<tr>
                <td>${r.nama}</td>
                <td>${r.qty}</td>
                <td>${r.satuan}</td>
                <td>Rp ${Number(r.harga).toLocaleString('id-ID')}</td>
            </tr>`;
        });
    } else {
        document.getElementById('info-resep').classList.add('d-none');
    }

    hitungHPP();
});

function hitungHPP() {
    const jumlah = parseFloat(document.getElementById('jumlah_produksi').value) || 0;
    const gas = parseFloat(document.getElementById('biaya_gas').value) || 0;
    const bensin = parseFloat(document.getElementById('biaya_bensin').value) || 0;
    const markup = parseFloat(document.getElementById('markup').value) || 0;

    let totalBahan = 0;
    resepData.forEach(r => {
        totalBahan += r.harga * r.qty;
    });

    const totalBiaya = totalBahan + gas + bensin;
    const hpp = jumlah > 0 ? totalBiaya / jumlah : 0;
    const hargaJualRaw = hpp * (1 + markup / 100);
    const hargaJual = Math.round(hargaJualRaw / 500) * 500;

    document.getElementById('hpp-display').value = Math.round(hpp).toLocaleString('id-ID');
    document.getElementById('harga-jual-display').value = hargaJual.toLocaleString('id-ID');
}

document.getElementById('jumlah_produksi').addEventListener('input', hitungHPP);
document.getElementById('biaya_gas').addEventListener('input', hitungHPP);
document.getElementById('biaya_bensin').addEventListener('input', hitungHPP);
document.getElementById('markup').addEventListener('input', hitungHPP);
</script>
@endsection