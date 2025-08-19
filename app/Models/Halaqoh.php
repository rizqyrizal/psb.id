<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halaqoh extends Model
{
    use HasFactory;
    protected $table = 'halaqoh';
    protected $fillable = ['nama'];

    public function pembimbing() {
        return $this->hasMany(Pembimbing::class, 'nama_id');
    }
}
