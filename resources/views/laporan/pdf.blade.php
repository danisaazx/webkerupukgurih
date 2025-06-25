<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        p {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .right {
            text-align: right;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Bulanan</h2>
    <p>Bulan: {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th class="center">Tanggal</th>
                <th>Nama Produk</th>
                <th class="center">Qty</th>
                <th class="right">HPP</th>
                <th class="right">Subtotal</th>
                <th class="right">Profit</th>
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
                        <td class="center">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>
                            {{ $d->product->name }}
                            @if($d->product->varian)
                                - {{ $d->product->varian }}
                            @endif
                        </td>
                        <td class="center">{{ $d->qty }}</td>
                        <td class="right">Rp {{ number_format($d->product->harga_beli, 0, ',', '.') }}</td>
                        <td class="right">Rp {{ number_format($d->total_harga, 0, ',', '.') }}</td>
                        <td class="right">Rp {{ number_format($profit, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="right">Total Penjualan:</td>
                <td class="right" colspan="2">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="right">Total Profit:</td>
                <td class="right" colspan="2">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="text-align: right; margin-top: 40px;">Dicetak pada: {{ \Carbon\Carbon::now()->setTimeZone('Asia/Jakarta')->format('d/m/Y H:i') }}</p>
</body>
</html>
