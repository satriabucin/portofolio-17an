<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\Pendaftar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    public $pendaftar;

    /**
     * Create a new job instance.
     */
    public function __construct(Pendaftar $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) {
            Log::warning("Fonnte token is missing. Skipping WhatsApp notification for {$this->pendaftar->no_hp}");
            return;
        }

        $numericPhone = preg_replace('/[^0-9]/', '', $this->pendaftar->no_hp);
        if (strlen($numericPhone) < 9) {
            Log::info("Invalid phone number format for {$this->pendaftar->nama} ({$this->pendaftar->no_hp}). Skipping WhatsApp notification.");
            return;
        }

        $phone = preg_replace('/^0/', '62', $this->pendaftar->no_hp);
        
        $message = "Halo *{$this->pendaftar->nama}*,\n\n";
        
        if ($this->pendaftar->status_verifikasi == 'Disetujui') {
            $message .= "Pendaftaran Anda untuk lomba 17-an telah *DISETUJUI*.\n\n";
            $message .= "*Jadwal Lomba Anda:*\n";
            foreach ($this->pendaftar->lombas as $lomba) {
                $waktu = $lomba->jadwal_waktu ? date('d M Y, H:i', strtotime($lomba->jadwal_waktu)) : 'Menunggu Info';
                $lokasi = $lomba->lokasi ?: 'Menunggu Info';
                $message .= "- *{$lomba->nama_lomba}*\n  Waktu: {$waktu}\n  Lokasi: {$lokasi}\n\n";
            }
            $message .= "Silakan cek status dan download kupon Anda di: " . url('/cek-status');
        } else if ($this->pendaftar->status_verifikasi == 'Ditolak') {
            $message .= "Mohon maaf, pendaftaran Anda untuk lomba 17-an *DITOLAK*.\n\n";
            $message .= "Catatan Panitia: " . ($this->pendaftar->catatan_admin ?: '-') . "\n\n";
            $message .= "Terima kasih atas partisipasi Anda.";
        } else {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Optional
            ]);

            if ($response->successful()) {
                Log::info("Fonnte WA Notification sent to {$phone}. Response: " . $response->body());
            } else {
                Log::error("Fonnte WA Error for {$phone}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Fonnte WA Exception: " . $e->getMessage());
        }
    }
}
