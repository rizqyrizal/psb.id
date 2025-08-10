<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwals';

    protected $fillable = [
        'kelas_id',
        'pembimbing_id',
        'mapel_id',

        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }
    public function mapel()
    {
        return $this->belongsTo(Pembimbing::class, 'mapel_id');
    }
}
