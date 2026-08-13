<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lomba;
use Inertia\Inertia;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PublicController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome');
    }

    public function daftar()
    {
        $lombas = Lomba::all();
        // Append current participant count to each lomba so React can render Sisa Kuota
        foreach ($lombas as $lomba) {
            $lomba->peserta_count = DB::table('pendaftar_lomba')->where('id_lomba', $lomba->id)->count();
        }
        return Inertia::render('Daftar', ['lombas' => $lombas]);
    }

    public function storeDaftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'blok_rumah' => 'required|string|max:255',
            'rt' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'lombas' => 'required|array',
            'lombas.*' => 'exists:lombas,id'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Pesimistic Locking to prevent race condition on quotas
                $lombas = Lomba::whereIn('id', $request->lombas)->lockForUpdate()->get();

                foreach ($lombas as $lomba) {
                    if (!is_null($lomba->kuota)) {
                        $currentParticipants = DB::table('pendaftar_lomba')->where('id_lomba', $lomba->id)->count();
                        if ($currentParticipants >= $lomba->kuota) {
                            throw new \Exception("Mohon maaf, kuota untuk lomba {$lomba->nama_lomba} sudah penuh!");
                        }
                    }
                }

                $pendaftar = Pendaftar::create([
                    'nama' => $request->nama,
                    'blok_rumah' => $request->blok_rumah,
                    'rt' => $request->rt,
                    'no_hp' => $request->no_hp,
                    'tahun_acara' => date('Y'),
                    'status_verifikasi' => 'Menunggu Verifikasi',
                ]);

                foreach ($request->lombas as $id_lomba) {
                    DB::table('pendaftar_lomba')->insert([
                        'id_pendaftar' => $pendaftar->id,
                        'id_lomba' => $id_lomba,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
            return redirect(url('/daftar'))->with('success', 'Pendaftaran berhasil dikirim dan sedang menunggu verifikasi panitia.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function cekStatus()
    {
        return Inertia::render('CekStatus');
    }

    public function cariStatus(Request $request)
    {
        $pendaftars = Pendaftar::where('no_hp', $request->no_hp)->get();
        return Inertia::render('CekStatus', ['pendaftars' => $pendaftars]);
    }

    public function galeri()
    {
        $imageFiles = glob(public_path('images/*.JPG'));
        $images = [];
        foreach($imageFiles as $file) {
            $images[] = basename($file);
        }
        return Inertia::render('Galeri', ['images' => $images]);
    }

    public function downloadTiket($id)
    {
        $pendaftar = Pendaftar::with('lombas')->findOrFail($id);
        if ($pendaftar->status_verifikasi !== 'Disetujui') {
            abort(403, 'Tiket hanya bisa diunduh untuk pendaftaran yang disetujui.');
        }

        $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($pendaftar->id . '-' . $pendaftar->nama));
        
        $pdf = Pdf::loadView('tiket', compact('pendaftar', 'qrCode'));
        return $pdf->download('Tiket-17an-' . $pendaftar->nama . '.pdf');
    }
}
