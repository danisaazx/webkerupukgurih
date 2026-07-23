<?php
namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class LaporanBulananExport implements FromView
{
    protected $bulan;
    protected $tanggal;

    public function __construct($bulan, $tanggal = null)
    {
        $this->bulan = $bulan;
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        // Ambil data transaksi dan detailnya
        if ($this->tanggal) {
            $awal = Carbon::parse($this->tanggal)->startOfDay();
            $akhir = Carbon::parse($this->tanggal)->endOfDay();
        } else {
            $awal = Carbon::parse($this->bulan)->startOfMonth();
            $akhir = Carbon::parse($this->bulan)->endOfMonth();
        }

        $transaksi = Transaction::with('details.product')->whereBetween('tanggal_pembelian', [$awal, $akhir])->get();

        $totalPenjualan = 0;
        $totalProfit = 0;
        $totalQty = 0;

        foreach ($transaksi as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->hpp ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->qty * $d->harga_satuan;
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