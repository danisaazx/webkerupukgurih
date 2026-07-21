<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_id','product_id','qty', 'harga_satuan'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class);
    }
}
