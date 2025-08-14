<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
    'santri_id',
    'amount',
    'keterangan',
    'payment_method',
    'id_transaksi',
    'tipe_transaksi', 
    'tanggal_masuk'
    ];
        public function santri()
    {
        return $this->belongsTo(\App\Models\Santri::class, 'santri_id');
    }
}
