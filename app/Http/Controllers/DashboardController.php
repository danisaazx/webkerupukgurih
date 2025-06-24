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
        return view('dashboard', compact('products', 'transactions', 'lowStockProducts'));
    }
}
