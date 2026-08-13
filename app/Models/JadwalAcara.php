<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAcara extends Model
{
    use HasFactory;

    protected $fillable = [
        'waktu_mulai',
        'waktu_selesai',
        'kegiatan',
        'deskripsi',
        'lokasi',
        'id_lomba'
    ];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class, 'id_lomba');
    }
}
