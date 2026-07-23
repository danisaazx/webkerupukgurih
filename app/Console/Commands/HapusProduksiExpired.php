<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produksi;
use Carbon\Carbon;

class HapusProduksiExpired extends Command
{
    protected $signature = 'produksi:hapus-expired';
    protected $description = 'Kurangi stok produk dan soft-delete produksi yang sudah kadaluarsa';

    public function handle()
    {
        $today = Carbon::now()->startOfDay();

        // Ambil semua produksi yang sudah lewat expired date dan belum di-soft-delete
        $produksiExpired = Produksi::with('product')
            ->whereDate('expired_date', '<=', $today)
            ->get();

        $jumlahDiproses = 0;

        foreach ($produksiExpired as $produksi) {
            if ($produksi->product) {
                // Kurangi stok produk, jangan sampai minus
                $stokBaru = max(0, $produksi->product->stok - $produksi->jumlah_produksi);
                $produksi->product->update(['stok' => $stokBaru]);

                $this->info("Stok {$produksi->product->name} dikurangi {$produksi->jumlah_produksi}, sisa: {$stokBaru}");
            }

            // Soft delete record produksi (data tetap ada di DB, cuma disembunyikan)
            $produksi->delete();
            $jumlahDiproses++;
        }

        $this->info("Selesai! {$jumlahDiproses} produksi kadaluarsa telah diproses.");
        return 0;
    }
}