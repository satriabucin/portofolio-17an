<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Lomba;
use App\Models\Pendaftar;
use App\Models\ActivityLog;
use App\Jobs\SendWhatsAppNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (session('admin_id')) return redirect(url('/admin/dashboard'));
        return Inertia::render('Admin/Login');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_id' => $admin->id, 'admin_name' => $admin->nama_lengkap]);
            ActivityLog::create([
                'admin_id' => $admin->id,
                'action' => 'Login',
                'description' => 'Admin berhasil login ke sistem',
                'ip_address' => request()->ip()
            ]);
            return redirect(url('/admin/dashboard'));
        }
        return back()->with('error', 'Username atau Password salah');
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_name']);
        return redirect(url('/admin/login'));
    }

    public function dashboard()
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        
        $total_pendaftar = Pendaftar::count();
        $total_disetujui = Pendaftar::where('status_verifikasi', 'Disetujui')->count();
        
        $lombas = DB::table('lombas')
            ->leftJoin('pendaftar_lomba', 'lombas.id', '=', 'pendaftar_lomba.id_lomba')
            ->leftJoin('pendaftars', 'pendaftars.id', '=', 'pendaftar_lomba.id_pendaftar')
            ->select('lombas.nama_lomba', DB::raw('COUNT(CASE WHEN pendaftars.status_verifikasi = "Disetujui" THEN 1 END) as total_peserta'))
            ->groupBy('lombas.id', 'lombas.nama_lomba')
            ->orderByDesc('total_peserta')
            ->get();
            
        $verified_pendaftars = Pendaftar::where('status_verifikasi', '!=', 'Menunggu Verifikasi')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $all_pendaftars = Pendaftar::with('lombas')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Dashboard', [
            'total_pendaftar' => $total_pendaftar,
            'total_disetujui' => $total_disetujui,
            'lombas' => $lombas,
            'verified_pendaftars' => $verified_pendaftars,
            'all_pendaftars' => $all_pendaftars
        ]);
    }

    public function updatePendaftar(Request $request, $id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        
        // Data Filtering & Sanitization
        $no_hp = preg_replace('/[^0-9]/', '', $request->no_hp);
        if (str_starts_with($no_hp, '62')) {
            $no_hp = '0' . substr($no_hp, 2);
        }

        $request->merge([
            'nama' => strip_tags(trim($request->nama)),
            'blok_rumah' => strip_tags(trim($request->blok_rumah)),
            'rt' => strip_tags(trim($request->rt)),
            'no_hp' => $no_hp,
        ]);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'blok_rumah' => 'required|string|max:255',
            'rt' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update([
            'nama' => $request->nama,
            'blok_rumah' => $request->blok_rumah,
            'rt' => $request->rt,
            'no_hp' => $request->no_hp,
        ]);

        ActivityLog::create([
            'admin_id' => session('admin_id'),
            'action' => 'Update Pendaftar',
            'description' => "Memperbarui data pendaftar ID {$pendaftar->id} ({$pendaftar->nama})",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Data pendaftar berhasil diperbarui!');
    }

    public function deletePendaftar($id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        
        $pendaftar = Pendaftar::findOrFail($id);
        
        ActivityLog::create([
            'admin_id' => session('admin_id'),
            'action' => 'Hapus Pendaftar',
            'description' => "Menghapus pendaftar ID {$pendaftar->id} ({$pendaftar->nama})",
            'ip_address' => request()->ip()
        ]);
        
        $pendaftar->delete(); // This will cascade delete pendaftar_lomba because of onDelete('cascade')

        return back()->with('success', 'Pendaftar berhasil dihapus secara permanen!');
    }

    public function deleteLomba($id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        Lomba::destroy($id);
        return back()->with('success', 'Lomba berhasil dihapus');
    }

    public function pesertaLomba()
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        
        $lombas = Lomba::with(['pendaftars' => function($query) {
            $query->where('status_verifikasi', 'Disetujui')
                  ->orderBy('pendaftar_lomba.sesi', 'asc')
                  ->orderBy('pendaftar_lomba.tim', 'asc')
                  ->orderBy('nama', 'asc');
        }])->get();

        return Inertia::render('Admin/PesertaLomba', ['lombas' => $lombas]);
    }

    public function randomizeSesi(Request $request, $id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        
        $request->validate([
            'jumlah_sesi' => 'required|integer|min:1'
        ]);

        $lomba = Lomba::findOrFail($id);
        
        // Get approved participants for this lomba
        $participants = $lomba->pendaftars()->where('status_verifikasi', 'Disetujui')->get();
        
        if ($participants->isEmpty()) {
            return back()->with('success', 'Belum ada peserta yang disetujui untuk lomba ini.');
        }

        // Shuffle the participants randomly
        $shuffled = $participants->shuffle();
        
        // Determine number of sessions (cap at total participants)
        $jumlahSesi = min($request->jumlah_sesi, $shuffled->count());
        
        // Split into chunks evenly
        $chunks = $shuffled->split($jumlahSesi);
        
        $sesi = 1;
        foreach ($chunks as $chunk) {
            $timCount = 1;
            $pesertaDalamTim = 0;
            
            foreach ($chunk as $pendaftar) {
                // Determine team number for this session
                $timToAssign = null;
                if ($lomba->jumlah_anggota_per_tim > 1) {
                    $timToAssign = $timCount;
                    $pesertaDalamTim++;
                    
                    if ($pesertaDalamTim >= $lomba->jumlah_anggota_per_tim) {
                        $timCount++;
                        $pesertaDalamTim = 0;
                    }
                }
                
                // Update the pivot table
                \Illuminate\Support\Facades\DB::table('pendaftar_lomba')
                    ->where('id_lomba', $id)
                    ->where('id_pendaftar', $pendaftar->id)
                    ->update([
                        'sesi' => $sesi,
                        'tim' => $timToAssign
                    ]);
            }
            $sesi++;
        }
        
        ActivityLog::create([
            'admin_id' => session('admin_id'),
            'action' => 'Acak Sesi Lomba',
            'description' => "Mengacak {$shuffled->count()} peserta menjadi {$jumlahSesi} kelompok/sesi untuk lomba: {$lomba->nama_lomba}",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "Berhasil mengacak peserta menjadi {$jumlahSesi} kelompok/sesi!");
    }

    public function resetSesi(Request $request, $id)
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        
        $lomba = Lomba::findOrFail($id);
        
        \Illuminate\Support\Facades\DB::table('pendaftar_lomba')
            ->where('id_lomba', $id)
            ->update([
                'sesi' => null,
                'tim' => null
            ]);
            
        ActivityLog::create([
            'admin_id' => session('admin_id'),
            'action' => 'Reset Sesi Lomba',
            'description' => "Mereset ulang sesi untuk lomba: {$lomba->nama_lomba}",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "Berhasil mereset sesi lomba ke awal!");
    }

    public function lomba()
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        $lombas = Lomba::all();
        return Inertia::render('Admin/Lomba', ['lombas' => $lombas]);
    }

    public function storeLomba(Request $request)
    {
        $lomba = Lomba::create($request->all());
        ActivityLog::create([
            'admin_id' => session('admin_id'),
            'action' => 'Tambah Lomba',
            'description' => "Menambahkan lomba baru: {$lomba->nama_lomba}",
            'ip_address' => request()->ip()
        ]);
        return back()->with('success', 'Lomba ditambahkan');
    }

    public function pendaftar()
    {
        if (!session('admin_id')) return redirect(url('/admin/login'));
        $pendaftars = Pendaftar::with('lombas')->where('status_verifikasi', 'Menunggu Verifikasi')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Pendaftar', ['pendaftars' => $pendaftars]);
    }

    public function verifikasiPendaftar(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->status_verifikasi = $request->status;
        if ($request->has('catatan_admin')) {
            $pendaftar->catatan_admin = $request->catatan_admin;
        }
        $pendaftar->save();

        ActivityLog::create([
            'admin_id' => session('admin_id'),
            'action' => 'Verifikasi Pendaftar',
            'description' => "Mengubah status pendaftar ID {$pendaftar->id} menjadi {$request->status}",
            'ip_address' => request()->ip()
        ]);

        // Dispatch background job to send WA notification via Fonnte
        if ($request->action == 'setujui_wa' || $request->status == 'Ditolak') {
            SendWhatsAppNotification::dispatch($pendaftar);
        }

        return back()->with('success', 'Status pendaftar diperbarui. Notifikasi WhatsApp akan dikirim otomatis oleh sistem (jika token tersedia).');
    }

    public function exportExcel()
    {
        $pendaftars = Pendaftar::with('lombas')->get();
        $filename = "Rekap_Pendaftar_17an.csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        
        fputcsv($handle, ['ID', 'Nama', 'Blok/RT', 'No HP', 'Status', 'Lomba Diikuti']);
        
        foreach($pendaftars as $p) {
            $lombas = $p->lombas->pluck('nama_lomba')->join(', ');
            fputcsv($handle, [$p->id, $p->nama, $p->blok_rumah . '/' . $p->rt, $p->no_hp, $p->status_verifikasi, $lombas]);
        }
        
        fclose($handle);
        exit;
    }

    public function exportPdf()
    {
        $pendaftars = Pendaftar::with('lombas')->get();
        $pdf = Pdf::loadView('admin.report_pdf', compact('pendaftars'));
        return $pdf->download('Rekap_Pendaftar_17an.pdf');
    }

    public function auditLogs()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')->paginate(50);
        return Inertia::render('Admin/AuditLogs', ['logs' => $logs]);
    }
}
