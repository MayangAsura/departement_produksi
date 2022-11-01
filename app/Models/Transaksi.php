<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "t_sales";
    protected $fillable = ['kode','tgl', 'cust_id', 'jumlah', 'subtotal', 'diskon', 'ongkir', 'total_bayar', 'status'];

  
}
