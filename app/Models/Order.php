<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'nomor_antrean',
        'nama_pemesan',
        'detail_pesanan',
        'total_harga',
        'metode_pembayaran',
        'status'
    ];
}
