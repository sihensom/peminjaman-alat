<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali_aktual',
        'kondisi_alat',
        'keterangan',
        'received_by',
        'denda',
        'solusi',
        'denda_status',
        'metode_bayar',
        'tanggal_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kembali_aktual' => 'date',
            'tanggal_bayar' => 'datetime',
        ];
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
