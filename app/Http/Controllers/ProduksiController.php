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
    public function index()
    {
        $produksis = Produksi::latest()->get();
        return view('produksi.index', compact('produksis'));
    }

    public function create()
    {
        $products = Product::with('recipes.bahanBaku')->get();
        return view('produksi.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah_produksi' => 'required|integer|min:1',
            'tanggal_produksi' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->product_id);
            $recipes = ProductRecipe::where('product_id', $product->id)->get();

            if ($recipes->isEmpty()) {
                return back()->with('error', 'Produk ini belum memiliki resep.');
            }

            // Cek stok bahan baku cukup atau tidak
            foreach ($recipes as $resep) {
                $jumlahTerpakai = $resep->qty_per_batch;
                $bahan = BahanBaku::findOrFail($resep->bahan_baku_id);

                if ($bahan->stok < $jumlahTerpakai) {
                    return back()->with('error', "Stok bahan baku '{$bahan->name}' tidak mencukupi. Stok tersedia: {$bahan->stok}, dibutuhkan: {$jumlahTerpakai}");
                }
            }

            // Biaya operasional
            $biayaGas = $request->biaya_gas ?? 0;
            $biayaBensin = $request->biaya_bensin ?? 0;
            $totalOperasional = $biayaGas + $biayaBensin;

            // Hitung total biaya bahan baku
            $totalBiayaBahan = 0;
            foreach ($recipes as $resep) {
                $bahan = BahanBaku::find($resep->bahan_baku_id);
                if ($bahan) {
                    $totalBiayaBahan += $bahan->harga_beli * $resep->qty_per_batch;
                }
            }

            $totalBiaya = $totalBiayaBahan + $totalOperasional;

            // HPP per unit
            $hppPerUnit = $totalBiaya / $request->jumlah_produksi;

            // Harga jual otomatis dari margin
            $margin = $request->markup ?? 0;
            $hargaJual = $hppPerUnit * (1 + ($margin / 100));
            $hargaJual = round($hargaJual / 500) * 500;

            $produksi = Produksi::create([
                'product_id' => $product->id,
                'jumlah_produksi' => $request->jumlah_produksi,
                'tanggal_produksi' => $request->tanggal_produksi,
                'expired_date' => Carbon::parse($request->tanggal_produksi)->addDays(7),
                'biaya_gas' => $biayaGas,
                'biaya_bensin' => $biayaBensin,
            ]);

            foreach ($recipes as $resep) {
                $jumlahTerpakai = $resep->qty_per_batch;

                $bahanBaku = BahanBaku::findOrFail($resep->bahan_baku_id);
                $bahanBaku->stok -= $jumlahTerpakai;
                $bahanBaku->save();

                ProduksiDetail::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $resep->bahan_baku_id,
                    'qty_terpakai' => $jumlahTerpakai,
                ]);
            }

            // Update stok, HPP, dan harga jual produk
            $product->stok += $request->jumlah_produksi;
            $product->hpp = $hppPerUnit;
            $product->harga_jual = round($hargaJual);
            $product->save();

            DB::commit();
            return redirect()->route('produksi.index')->with('success', 'Produksi berhasil! HPP: Rp ' . number_format($hppPerUnit, 0, ',', '.') . ' | Harga Jual: Rp ' . number_format($hargaJual, 0, ',', '.'));
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Produksi $produksi)
    {
        $produksi->load('product', 'details.bahanBaku');
        return view('produksi.show', compact('produksi'));
    }

    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}