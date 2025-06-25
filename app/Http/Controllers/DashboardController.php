<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $lowStockProducts = Product::where('stok', '<', 10)->get();
        $transactions = Transaction::latest()->limit(10)->get();

        //logic untuk total penjualan dan total profit
        $totalProfit = 0;
        $totalQty= 0;
        $totalPenjualan = 0;
        foreach ($transactions as $t) {
            foreach ($t->details as $d) {
                $hpp = $d->product->harga_beli ?? 0;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $d->total_harga;
            }
        }


        return view('dashboard', compact('products', 'transactions', 'lowStockProducts', 'totalPenjualan', 'totalProfit'));
    }
}
