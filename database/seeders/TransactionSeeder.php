<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();

        $products = Product::all();

        if ($products->count() == 0) {
            $this->command->warn('Tidak ada data produk. Seeder transaksi dilewati.');
            return;
        }

        foreach (range(1, 20) as $i) {
            $product = $products->random();
            $qty = rand(1, 10);
            $harga_satuan = $product->harga_jual ?? rand(5000, 20000);
            $total_harga = $qty * $harga_satuan;

            Transaction::create([
                'nama_pembeli'  => 'Pembeli ' . Str::random(5), // hapus jika tidak pakai nama_pembeli
                'product_id'    => $product->id,
                'qty'           => $qty,
                'harga_satuan'  => $harga_satuan,
                'total_harga'   => $total_harga,
                'tanggal_penjualan'       => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
