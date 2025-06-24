<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BahanBaku;
use App\Models\Produksi;
use App\Models\ProduksiDetail;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produksis = Produksi::all();
        return view('produksi.index', compact('produksis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        return view('produksi.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah_produksi' => 'required|integer|min:1',
            'tanggal_produksi' => 'required|date'
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->product_id);
            $recipes = ProductRecipe::where('product_id', $product->id)->get();

            if ($recipes->isEmpty())
            {
                return back()->with('error', 'Produk ini belum memiliki resep.');
            }

            $produksi = Produksi::create([
                'product_id' => $product->id,
                'jumlah_produksi' => $request->jumlah_produksi,
                'tanggal_produksi' => $request->tanggal_produksi,
                'expired_date' => Carbon::parse($request->tanggal_produksi)->addMonth(),
            ]);

            foreach ($recipes as $resep) {
                $jumlahTerpakai= $resep->qty_per_batch * $request->jumlah_produksi;

                ProduksiDetail::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $resep->bahan_baku_id,
                    'qty_terpakai' => $jumlahTerpakai,
                ]);
            }

            // Tambah stok produk
            $product->stok += $request->jumlah_produksi;
            $product->save();

            DB::commit();
            return redirect()->route('produksi.index')->with('success', 'Produksi Berhasil ditambahkan!.');
        } catch(\Throwable $e) {
            DB::rollback();
            dd($e->getMessage());
            return back()->with('error', 'Terjadi Kesalahan'. $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Produksi $produksi)
    {
        $produksi->load('product', 'details.bahanBaku');
        return view('produksi.show', compact('produksi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
