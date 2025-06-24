<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;
    protected $table = 'produksis';
    protected $fillable = ['product_id', 'tanggal_produksi', 'jumlah_produksi', 'expired_date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(ProduksiDetail::class);
    }
}
