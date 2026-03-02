<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'kategori_id',
        'kode_alat',
        'nama_alat',
        'deskripsi',
        'jumlah_total',
        'jumlah_tersedia',
        'kondisi',
        'gambar',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function isAvailable(int $jumlah = 1): bool
    {
        return $this->jumlah_tersedia >= $jumlah;
    }
}
