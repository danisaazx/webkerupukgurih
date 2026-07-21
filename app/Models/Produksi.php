<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;
    protected $table = 'produksis';
    protected $fillable = ['product_id', 'tanggal_produksi', 'jumlah_produksi', 'expired_date', 'biaya_gas', 'biaya_bensin'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(ProduksiDetail::class);
    }
    public function getStatusExpiredAttribute()
    {
    $today = now()->startOfDay();
    $expired = \Carbon\Carbon::parse($this->expired_date)->startOfDay();
    $daysLeft = $today->diffInDays($expired, false);

    if ($daysLeft < 0) {
        return [
            'status' => 'expired',
            'label'  => 'Kadaluarsa',
            'color'  => 'red',
            'days'   => abs($daysLeft),
        ];
    } elseif ($daysLeft <= 7) {
        return [
            'status' => 'warning',
            'label'  => 'Segera Kadaluarsa',
            'color'  => 'orange',
            'days'   => $daysLeft,
        ];
    }

    return [
        'status' => 'safe',
        'label'  => 'Aman',
        'color'  => 'green',
        'days'   => $daysLeft,
    ];
    }
}
