<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lomba;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyHariH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:hari-h';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi WA H-Hari untuk lomba hari ini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = date('Y-m-d');
        $lombasToday = Lomba::whereDate('jadwal_waktu', $today)->get();
        
        $token = env('FONNTE_TOKEN');
        if (!$token) {
            $this->error("Fonnte token missing.");
            Log::warning("Cron NotifyHariH: Fonnte token is missing.");
            return;
        }

        if ($lombasToday->isEmpty()) {
            $this->info("Tidak ada lomba hari ini ($today).");
            return;
        }

        $sentCount = 0;

        // Collect all approved pendaftars who have a lomba today
        $pendaftarsToNotify = \App\Models\Pendaftar::where('status_verifikasi', 'Disetujui')
            ->whereHas('lombas', function($q) use ($today) {
                $q->whereDate('jadwal_waktu', $today);
            })
            ->with(['lombas' => function($q) use ($today) {
                $q->whereDate('jadwal_waktu', $today);
            }])
            ->get();

        foreach ($pendaftarsToNotify as $pendaftar) {
            $numericPhone = preg_replace('/[^0-9]/', '', $pendaftar->no_hp);
            if (strlen($numericPhone) < 9) {
                continue; // Skip invalid phone numbers
            }
            
            $phone = preg_replace('/^0/', '62', $pendaftar->no_hp);
            
            $message = "Halo *{$pendaftar->nama}*,\n\n";
            $message .= "Ini adalah PENGINGAT OTOMATIS bahwa lomba yang Anda ikuti akan diselenggarakan *HARI INI*:\n\n";
            
            foreach ($pendaftar->lombas as $lomba) {
                $waktu = date('H:i', strtotime($lomba->jadwal_waktu));
                $lokasi = $lomba->lokasi ?: 'Menunggu Info';
                $message .= "🏆 *{$lomba->nama_lomba}*\n";
                $message .= "🕒 Jam: {$waktu}\n";
                $message .= "📍 Lokasi: {$lokasi}\n\n";
            }
            
            $message .= "Jangan lupa bawa semangatmu!\n";
            $message .= "Sampai jumpa di lokasi lomba!\n\n_Panitia 17-an RT_";

            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

                if ($response->successful()) {
                    $sentCount++;
                } else {
                    Log::error("Fonnte WA Error (Hari-H) for {$phone}: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Fonnte WA Exception (Hari-H): " . $e->getMessage());
            }
        }

        $this->info("Berhasil mengirim {$sentCount} pesan pengingat WA (digabung per warga) untuk lomba hari ini.");
    }
}
