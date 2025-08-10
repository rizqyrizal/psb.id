<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;
    protected $table = 'tugas';

    protected $fillable = [
        'target',
        'satuan',
        'nama_tugas',
        'deskripsi',
        'jumlah_tugas',
        'waktu_berakhir',
    ];

    public function catatan() {
        return $this->hasMany(Catatan::class, 'tugas_id');
    }
}
