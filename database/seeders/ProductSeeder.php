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
            'hpp' => 5000,
            'harga_jual' => 8000,
        ]);

        Product::create([
            'name' => 'Kerupuk Udang',
            'varian' => 'Original',
            'stok' => 50,
            'hpp' => 7000,
            'harga_jual' => 10000,
        ]);

        Product::create([
            'name' => 'Kerupuk Ikan',
            'varian' => 'Asin',
            'stok' => 80,
            'hpp' => 6000,
            'harga_jual' => 9000,
        ]);
    }
}
