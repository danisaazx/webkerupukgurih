<?php
namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class LaporanBulananExport implements FromView
{
    protected $bulan;

    public function __construct($bulan)
    {
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        // Ambil data transaksi dan detailnya
        $awal = Carbon::parse($this->bulan)->startOfMonth();
        $akhir = Carbon::parse($this->bulan)->endOfMonth();

        $transaksi = Transaction::with('details.product')->whereBetween('tanggal_penjualan', [$awal, $akhir])->get();

        $totalPenjualan = 0;
        $totalProfit = 0;
        $totalQty = 0;

        foreach ($transaksi as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->harga_beli ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->total_harga;
            }
        }

        return view('laporan.excel', [
            'transaksi' => $transaksi,
            'bulan' => $this->bulan,
            'totalPenjualan' => $totalPenjualan,
            'totalProfit' => $totalProfit,
            'totalQty' => $totalQty,
        ]);
    }
}

?>