<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Kerupuk Bawang',
            'varian' => 'Pedas',
            'stok' => 100,
            'harga_beli' => 5000,
            'harga_jual' => 8000,
        ]);

        Product::create([
            'name' => 'Kerupuk Udang',
            'varian' => 'Original',
            'stok' => 50,
            'harga_beli' => 7000,
            'harga_jual' => 10000,
        ]);

        Product::create([
            'name' => 'Kerupuk Ikan',
            'varian' => 'Asin',
            'stok' => 80,
            'harga_beli' => 6000,
            'harga_jual' => 9000,
        ]);
    }
}
