<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'varian', 'stok', 'hpp', 'harga_jual'];

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
    public function recipes() 
    {
        return $this->hasMany(ProductRecipe::class);
    }
    public function produksis()
    {
        return $this->hasMany(Produksi::class);
    }
}
