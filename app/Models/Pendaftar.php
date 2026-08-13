<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    protected $guarded = [];

    public function lombas()
    {
        return $this->belongsToMany(Lomba::class, 'pendaftar_lomba', 'id_pendaftar', 'id_lomba')->withPivot('sesi', 'tim');
    }
}
