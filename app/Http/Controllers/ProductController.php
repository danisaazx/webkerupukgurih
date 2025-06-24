<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with('recipes.bahanBaku');
        $lowStockProducts = Product::where('stok', '<', 10)->get();
            if($request->filled('keyword'))
            {
                $keywords = explode(' ', $request->keyword);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word){
                        $q->where(function($sub) use ($word){
                            $sub->where('name', 'like', "%$word%")
                            ->orWhere('varian', 'like', "%$word%");

                        });
                    }
                });
            }
            $products = $query->get();
        return view('products.index', compact('products', 'lowStockProducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bahanBakus = BahanBaku::all();
        return view('products.create', compact('bahanBakus'));
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
        $hpp = 0;

        // Hitung total HPP dari semua bahan
        foreach ($request->bahan_baku_id as $i => $bahanId)
        {
            $jumlah = $request->qty_per_batch[$i];
            $satuan = strtolower($request->satuan[$i]);

            // Konversi gram ke kg
            if($satuan === 'gram') {
                $jumlah = $jumlah / 1000;
                $satuan = 'kg';
            }

            // Ambil harga beli bahan baku
            $bahan = BahanBaku::find($bahanId);
            if ($bahan) {
                $hpp += $bahan->harga_beli * $jumlah;
            }
        }

        // Simpan produk dengan HPP
        $product = Product::create([
            'name' => $request->name,
            'varian' => $request->varian,
            'harga_beli' => $hpp,
            'harga_jual' => $request->harga_jual,
        ]);

        // Simpan resep
        foreach ($request->bahan_baku_id as $i => $bahanId)
        {
            $jumlah = $request->qty_per_batch[$i];
            $satuan = strtolower($request->satuan[$i]);

            if($satuan === 'gram') {
                $jumlah = $jumlah / 1000;
                $satuan = 'kg';
            }

            ProductRecipe::create([
                'product_id' => $product->id,
                'bahan_baku_id' => $bahanId,
                'qty_per_batch' => $jumlah,
                'satuan' => $satuan,
            ]);
        }

        DB::commit();
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    } catch(\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan! ' . $e->getMessage());
    }
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('recipes')->findOrFail($id);
        $bahanBakus = BahanBaku::all();
        return view('products.edit', compact('product', 'bahanBakus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    DB::beginTransaction();
    try {
        $product = Product::findOrFail($id);

        // Hitung ulang HPP berdasarkan resep yang diinput
        $hpp = 0;
        foreach ($request->bahan_baku_id as $i => $bahanId)
        {
            $jumlah = $request->qty_per_batch[$i];
            $satuan = strtolower($request->satuan[$i]);

            if ($satuan === 'gram') {
                $jumlah = $jumlah / 1000;
                $satuan = 'kg';
            }

            $bahan = BahanBaku::find($bahanId);
            if ($bahan) {
                $hpp += $bahan->harga_beli * $jumlah;
            }
        }

        // Update produk dengan HPP baru
        $product->update([
            'name' => $request->name,
            'varian' => $request->varian,
            'harga_beli' => $hpp,
            'harga_jual' => $request->harga_jual,
        ]);

        // Hapus semua resep lama
        $product->recipes()->delete();

        // Simpan ulang resep baru
        foreach ($request->bahan_baku_id as $i => $bahanId)
        {
            $jumlah = $request->qty_per_batch[$i];
            $satuan = strtolower($request->satuan[$i]);

            if ($satuan === 'gram') {
                $jumlah = $jumlah / 1000;
                $satuan = 'kg';
            }

            ProductRecipe::create([
                'product_id' => $product->id,
                'bahan_baku_id' => $bahanId,
                'qty_per_batch' => $jumlah,
                'satuan' => $satuan,
            ]);
        }

        DB::commit();
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!.');
    } catch (\Throwable $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan'. $e->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->recipes()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!.');
    }
}
