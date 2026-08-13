<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lomba;
use App\Models\Pendaftar;

class ApiController extends Controller
{
    public function getLombas()
    {
        $lombas = Lomba::all();
        return response()->json([
            'status' => 'success',
            'data' => $lombas
        ]);
    }

    public function getPendaftars()
    {
        $pendaftars = Pendaftar::with('lombas')->get();
        return response()->json([
            'status' => 'success',
            'data' => $pendaftars
        ]);
    }
}
