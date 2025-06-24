<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['nama_pembeli','tanggal_penjualan'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
