<?php

namespace App\Http\Controllers;

use App\Models\JadwalAcara;
use App\Models\Lomba;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalAcara::with('lomba')->orderBy('waktu_mulai', 'asc')->get();
        return Inertia::render('Jadwal', ['jadwals' => $jadwals]);
    }

    public function adminIndex()
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        $jadwals = JadwalAcara::with('lomba')->orderBy('waktu_mulai', 'asc')->get();
        $lombas = Lomba::all();
        return Inertia::render('Admin/Jadwal', ['jadwals' => $jadwals, 'lombas' => $lombas]);
    }

    public function store(Request $request)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        $request->validate([
            'waktu_mulai' => 'required|date',
            'kegiatan' => 'required|string',
        ]);

        JadwalAcara::create($request->all());
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        $jadwal = JadwalAcara::findOrFail($id);
        $jadwal->update($request->all());
        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        JadwalAcara::destroy($id);
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
