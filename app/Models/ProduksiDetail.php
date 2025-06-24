<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiDetail extends Model
{
    use HasFactory;
    protected $table = 'produksi_details';
    protected $fillable = ['produksi_id', 'bahan_baku_id', 'qty_terpakai'];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class);
    }
}
