<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{

    use HasFactory;

    protected $table = 'pembimbings';

    protected $fillable = [
        'kelas_id',

        'nama',
        'nip',
        'nuptk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'email',
        'foto',
        'wali_kelas',
        'mapel',
    ];

    protected $casts = [
    'wali_kelas_type' => 'array',
    'mapel' => 'array', // Ini juga perlu diubah karena Anda menggunakan multiple() di sini
];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function materi() {
        return $this->hasMany(Materi::class, 'pembimbing_id');
    }
    public function materii() {
        return $this->hasMany(Materi::class, 'mapel_id');
    }
    public function jadwal() {
        return $this->hasMany(Jadwal::class, 'pembimbing_id');
    }
    public function jadwaal() {
        return $this->hasMany(Jadwal::class, 'mapel_id');
    }
    public function absensi() {
        return $this->hasMany(Absensi::class, 'pembimbing_id');
    }
    
}
