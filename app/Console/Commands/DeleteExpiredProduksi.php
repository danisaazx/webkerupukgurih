<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produksi;

class DeleteExpiredProduksi extends Command
{
    protected $signature = 'produksi:delete-expired';
    protected $description = 'Hapus data produksi yang sudah kadaluarsa lebih dari 10 hari';

    public function handle()
    {
        $batasTanggal = \Carbon\Carbon::now()->subDays(10);
        $produksiExpired = Produksi::whereDate('expired_date', '<', $batasTanggal)->get();
        $jumlah = $produksiExpired->count();
        foreach ($produksiExpired as $produksi) {
            $produksi->delete(); // ini sekarang soft delete otomatis
        }
        $this->info("Berhasil menyembunyikan {$jumlah} data produksi yang sudah kadaluarsa lebih dari 10 hari.");
    }
}
