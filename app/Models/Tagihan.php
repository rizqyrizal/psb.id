<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihans'; // atau 'tagihan' jika tidak jamak

    protected $fillable = [
        'santri_id',
        'keterangan',
        'jatuh_tempo',
        'amount',
        'lunas',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'lunas' => 'boolean',
    ];

    // Relasi ke model Santri (asumsi ada)
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}
