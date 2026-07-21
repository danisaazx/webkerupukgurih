<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','nama_pembeli','tanggal_pembelian'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
