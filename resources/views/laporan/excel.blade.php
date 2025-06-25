<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Bulanan</title>
</head>
<body>
    <h2 style="text-align: center;">Laporan Penjualan Bulanan</h2>
    <p>Bulan: {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</p>

    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                            <th>Tanggal</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>HPP</th>
                            <th>Subtotal</th>
                            <th>Profit</th>
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
                                <td style="width: 180px;">
                                    <span>
                                        {{ \Carbon\Carbon::parse($d->tanggal_penjualan)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td style="width: 180px;">
                                    <span>{{ $d->product->name }}</span>
                                    @if($d->product->varian)
                                        <span>{{ $d->product->varian }}</span>
                                    @endif
                                </td>
                                <td style="width: 180px;" align="left">
                                    <span>{{ $d->qty }}</span>
                                </td>
                                <td style="width: 180px;">Rp {{ number_format($d->product->harga_beli, 0, ',', '.') }}</td>
                                <td style="width: 180px;">Rp {{ number_format($d->total_harga, 0, ',', '.') }}</td>
                                <td style="width: 180px;">
                                    <span>Rp {{ number_format($profit, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        @endforeach
                    </tbody>
        <tfoot>
            <tr>
                <td colspan="5" align="right"><strong>Total Penjualan:</strong></td>
                <td >Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" align="right"><strong>Total Profit:</strong></td>
                <td >Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" align="right"><strong>Total Produk Terjual:</strong></td>
                <td align="left">{{$totalQty}}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
