<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $fillable = [
        'mapel_id',
        'Pembimbing_id',
        'nama',
        'desc'
    ];

    public function mapel() {
        return $this->belongsTo(Pembimbing::class, 'mapel_id');
    }
    public function pembimbing() {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }
}


