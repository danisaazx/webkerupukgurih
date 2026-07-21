<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $transaction = Transaction::with('product')->latest()->get();
        $query = Transaction::with('details','user');
        if ($request->filled('tanggal'))
        {
            $query->whereDate('tanggal_pembelian', $request->tanggal);
        }

        $transaction = $query->latest()->paginate(10);
        return view('transaksi.index', compact('transaction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('stok', '>', 0)->get();
        return view('transaksi.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
    try {
        $request->validate([
            'nama_pembeli' => 'required|string',
        ]);
        // dd($request->nama_pembeli);
        $transaksi = Transaction::create([
            'user_id' => auth()->id(),
            'nama_pembeli' => $request->nama_pembeli,
            'tanggal_pembelian' => now(),
        ]);

        foreach ($request->product_id as $i => $idProduk) {
            $produk = Product::findOrFail($idProduk);
            $qty = (int)$request->qty[$i];

            if ($produk->stok < $qty) {
                throw new \Exception("Stok untuk {$produk->name} tidak mencukupi.");
            }

            TransaksiDetail::create([
                'transaction_id' => $transaksi->id,
                'product_id' => $produk->id,
                'qty' => $qty,
                'harga_satuan' => $produk->harga_jual,
            ]);

            // Kurangi stok produk
            $produk->stok -= $qty;
            $produk->save();
        }

        DB::commit();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    } catch (\Exception $e) {
        DB::rollback();
        // dd($e->getMessage());
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
