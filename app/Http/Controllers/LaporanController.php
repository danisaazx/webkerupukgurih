<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBulananExport;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        $awal = Carbon::parse($bulan)->startOfMonth();
        $akhir = Carbon::parse($bulan)->endOfMonth();

        $transaksi = Transaction::with('product')
        ->whereBetween('tanggal_penjualan', [$awal, $akhir])
        ->get();

        $totalProfit = 0;
        $totalQty= 0;
        $totalPenjualan = 0;
        foreach ($transaksi as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->harga_beli ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->total_harga;
            }
        }

        return view('laporan.bulanan', compact('transaksi', 'bulan', 'totalPenjualan', 'totalProfit','totalQty'));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        return Excel::download(new LaporanBulananExport($bulan), 'laporan_bulanan_' . $bulan . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        $awal = Carbon::parse($bulan)->startOfMonth();
        $akhir = Carbon::parse($bulan)->endOfMonth();

        $transaksi = Transaction::with('product')
        ->whereBetween('tanggal_penjualan', [$awal, $akhir])
        ->get();

        $totalProfit = 0;
        $totalQty= 0;
        $totalPenjualan = 0;
        foreach ($transaksi as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->harga_beli ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->total_harga;
            }
        }

        $pdf = PDF::loadView('laporan.pdf', compact('transaksi', 'bulan', 'totalPenjualan', 'totalProfit'));
        return $pdf->download('laporan_bulanan' . $bulan . '.pdf');
    }
}
