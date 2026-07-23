<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Produksi;

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
                $hpp = $d->product->hpp ?? 0;
                $penjualan = $d->qty * $d->harga_satuan;
                $produkProfit = ($d->harga_satuan - $hpp) * $d->qty;
                $totalProfit += $produkProfit;
                $totalQty += $d->qty;
                $totalPenjualan += $penjualan;
            }
        }

        // Produksi yang sudah/akan kadaluarsa (dalam 7 hari ke depan)
        $produksiExpired = Produksi::with('product')
            ->whereHas('product')
            ->whereDate('expired_date', '<=', now()->addDays(7))
            ->orderBy('expired_date', 'asc')
            ->get();

        return view('dashboard', compact('products', 'transactions', 'lowStockProducts', 'totalPenjualan', 'totalProfit', 'produksiExpired'));
    }
}