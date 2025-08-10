<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'jenjang',
        'jurusan',
    ];
    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class, 'kelas_id');
    }
    public function catatan(){
        return $this->hasMany(Catatan::class, 'kelas_id');
    }
    public function absensi(){
        return $this->hasMany(Absensi::class, 'kelas_id');
    }
    public function jadwal(){
        return $this->hasMany(Jadwal::class, 'kelas_id');
    }
    
}
