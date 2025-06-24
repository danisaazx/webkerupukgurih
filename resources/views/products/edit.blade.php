@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5">
                    <i class="bi bi-pencil-square me-2"></i>Edit Produk
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', $product->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Nama Produk" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Varian</label>
                            <input type="text" name="varian" value="{{ $product->varian }}" class="form-control" placeholder="Varian" required>
                        </div>
                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Harga Jual</label>
                                <input type="number" step="0.01" name="harga_jual" value="{{ $product->harga_jual }}" class="form-control" placeholder="Harga Jual" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label><strong>Estimasi HPP per Produk:</strong></label><br>
                            <span id="hpp-estimasi" style="font-weight:bold; color:#0d6efd;">Rp 0</span>
                        </div>

                        <hr>
                        <label class="form-label fw-semibold mb-2">Resep Bahan Baku</label>
                        <div id="resep-container">
                            @foreach($product->recipes as $index => $recipe)
                                <div class="row g-2 align-items-end mb-2">
                                    <div class="col-md-5">
                                        <select name="bahan_baku_id[]" class="form-select" required>
                                            <option value="" disabled>Pilih Bahan Baku</option>
                                            @foreach($bahanBakus as $bahan)
                                                <option value="{{ $bahan->id }}" {{ $bahan->id == $recipe->bahan_baku_id ? 'selected' : '' }}>
                                                    {{ $bahan->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" step="0.01" name="qty_per_batch[]" value="{{ $recipe->qty_per_batch }}" class="form-control" placeholder="Qty per batch" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="satuan[]" value="{{ $recipe->satuan }}" class="form-control" placeholder="Satuan" required>
                                    </div>
                                    <div class="col-md-1 d-grid">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="tambahResep()">
                            <i class="bi bi-plus"></i> Tambah Bahan Baku
                        </button>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gradient-primary px-4 fw-semibold">
                                <i class="bi bi-save me-1"></i> Perbarui
                            </button>
                        </div>
                    </form>
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
    .btn-gradient-primary {
        background: linear-gradient(135deg,#0d6efd 60%,#3b82f6 100%) !important;
        color: #fff !important;
        border: none;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg,#3b82f6 60%,#0d6efd 100%) !important;
        color: #fff !important;
    }
</style>


<script>
    const bahanList = {
        @foreach($bahanBakus as $bahan)
            "{{ $bahan->id }}": {{ $bahan->harga_beli }},
        @endforeach
    };

function tambahResep() {
    const html = `
    <div class="row g-2 align-items-end mb-2">
        <div class="col-md-5">
            <select name="bahan_baku_id[]" class="form-select" required>
                <option value="" disabled selected>Pilih Bahan Baku</option>
                @foreach($bahanBakus as $bahan)
                    <option value="{{ $bahan->id }}">{{ $bahan->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="qty_per_batch[]" class="form-control" placeholder="Qty per batch" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="satuan[]" class="form-control" placeholder="Satuan" required>
        </div>
        <div class="col-md-1 d-grid">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>`;
    document.getElementById('resep-container').insertAdjacentHTML('beforeend', html);
}

function hitungHPP() {
    const rows = document.querySelectorAll('#resep-container .row');
    let totalHpp = 0;

    rows.forEach(row => {
        const select = row.querySelector('select[name="bahan_baku_id[]"]');
        const qtyInput = row.querySelector('input[name="qty_per_batch[]"]');
        const satuanInput = row.querySelector('input[name="satuan[]"]');

        const bahanId = select.value;
        const qty = parseFloat(qtyInput.value) || 0;
        const satuan = satuanInput.value.toLowerCase();

        const hargaBahan = bahanList[bahanId] || 0;

        // Konversi: jika satuan gram, ubah ke kg
        const konversi = satuan === 'gram' ? 0.001 : 1;

        totalHpp += hargaBahan * qty * konversi;
    });

    // Tampilkan dalam format rupiah
    document.getElementById('hpp-estimasi').textContent = 'Rp ' + totalHpp.toLocaleString('id-ID');
}

// Trigger otomatis saat ada perubahan
document.addEventListener('input', hitungHPP);
</script>
@endsection