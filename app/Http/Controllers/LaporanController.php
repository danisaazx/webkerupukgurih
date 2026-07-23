<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
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
        $tanggal = $request->input('tanggal');

         if ($tanggal) {
        // Kalau tanggal spesifik dipilih, filter by tanggal tersebut
        $awal = Carbon::parse($tanggal)->startOfDay();
        $akhir = Carbon::parse($tanggal)->endOfDay();
        } else {
            // Kalau tidak, pakai filter bulan seperti biasa
            $awal = Carbon::parse($bulan)->startOfMonth();
            $akhir = Carbon::parse($bulan)->endOfMonth();
        }

        $transaksi = Transaction::with('details.product')        ->whereBetween('tanggal_pembelian', [$awal, $akhir])
        ->get();

        $totalProfit = 0;
        $totalQty= 0;
        $totalPenjualan = 0;
        foreach ($transaksi as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->hpp ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->qty * $d->harga_satuan;
            }
        }

        $produkStok = Product::orderBy('name')->orderBy('varian')->get();

        return view('laporan.bulanan', compact('transaksi', 'bulan','tanggal', 'totalPenjualan', 'totalProfit','totalQty', 'produkStok'));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        $tanggal = $request->input('tanggal');

        if ($tanggal) {
        $namaFile = 'laporan_harian_' . $tanggal . '.xlsx';
        } else {
            $namaFile = 'laporan_bulanan_' . $bulan . '.xlsx';
        }

      return Excel::download(new LaporanBulananExport($bulan, $tanggal), $namaFile);


    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        $tanggal = $request->input('tanggal');

        if ($tanggal) {
            $awal = Carbon::parse($tanggal)->startOfDay();
            $akhir = Carbon::parse($tanggal)->endOfDay();
            $namaFile = 'laporan_harian_' . $tanggal . '.pdf';
        } else {
            $awal = Carbon::parse($bulan)->startOfMonth();
            $akhir = Carbon::parse($bulan)->endOfMonth();
            $namaFile = 'laporan_bulanan_' . $bulan . '.pdf';
        }
        $transaksi = Transaction::with('details.product')        ->whereBetween('tanggal_pembelian', [$awal, $akhir])
        ->get();

        $totalProfit = 0;
        $totalQty= 0;
        $totalPenjualan = 0;
        foreach ($transaksi as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->hpp ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->qty * $d->harga_satuan;
            }
        }

        $pdf = PDF::loadView('laporan.pdf', compact('transaksi', 'bulan', 'tanggal', 'totalPenjualan', 'totalProfit'));
        return $pdf->download($namaFile);
    }
}
