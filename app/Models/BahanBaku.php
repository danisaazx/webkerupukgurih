<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_bakus';

    protected $fillable = ['name', 'satuan', 'stok', 'harga_beli'];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }
}
