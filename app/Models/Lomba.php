<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lomba extends Model
{
    protected $guarded = [];

    public function pendaftars()
    {
        return $this->belongsToMany(Pendaftar::class, 'pendaftar_lomba', 'id_lomba', 'id_pendaftar')->withPivot('sesi', 'tim');
    }
}
