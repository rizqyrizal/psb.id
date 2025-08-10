<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;
    protected $table = 'santris';

    protected $fillable = [

        'kelas_id',
        'wali_kelas_id',
        'user_id',


        'nama',
        'nisn',
        'nis',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'email',
        'foto',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'wali_kelas_id');
    }
    public function catatan()
    {
        return $this->hasMany(Catatan::class, 'santri_id');
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'santri_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tagihan() {
        return $this->belongsTo(Tagihan::class, 'santri_id');
    }
}
