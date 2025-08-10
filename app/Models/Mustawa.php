<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mustawa extends Model
{
    use HasFactory;
    protected $table = 'mustawas';
    protected $fillable = ['nama'];

    public function pembimbing() {
        return $this->hasMany(Pembimbing::class, 'nama_id');
    }
}
