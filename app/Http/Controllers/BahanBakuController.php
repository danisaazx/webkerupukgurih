<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bahanBakus = BahanBaku::orderBy('name')->get();
        return view('bahan_baku.index', compact('bahanBakus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bahan_baku.create');
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
            'name'=>'required|string',
            'satuan'=>'required|string',
            'stok'=>'required|numeric|min:0',
            'harga_beli'=>'required|numeric|min:0',
        ]);

        BahanBaku::create($request->all());
        return redirect()->route('bahan_baku.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BahanBaku $bahanBaku)
    {
        return view('bahan_baku.edit', compact('bahanBaku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'name' => 'required|string',
            'satuan' => 'required|string',
            'stok' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $bahanBaku->update($request->all());
        return redirect()->route('bahan_baku.index')->with('success', 'Bahan baku berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BahanBaku $bahanBaku)
    {
        $bahanBaku->delete();
        return redirect()->route('bahan_baku.index')->with('Bahan baku Berhasil dihapus!');
    }
}
