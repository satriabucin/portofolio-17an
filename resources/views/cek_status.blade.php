<x-layout>
    <x-slot:title>Cek Status Pendaftaran</x-slot>

    <div class="container section">
        <div class="glass-card" style="padding: 40px; max-width: 600px; margin: 0 auto;">
            <h1 class="text-center mb-1">Cek Status Pendaftaran</h1>
            <p class="text-center mb-4" style="opacity: 0.8;">Masukkan Nomor HP yang Anda gunakan saat mendaftar lomba.</p>

            <form action="{{ url('/cek-status') }}" method="POST">
                @csrf
                <div class="form-group" style="display:flex; gap: 10px;">
                    <input type="tel" name="no_hp" class="form-control" required placeholder="08xxxxxxxxxx">
                    <button type="submit" class="btn btn-accent">Cari</button>
                </div>
            </form>

            @if(isset($pendaftars))
                <div style="margin-top: 30px;">
                    @if($pendaftars->count() > 0)
                        <h3 style="margin-bottom: 15px;">Hasil Pencarian:</h3>
                        @foreach($pendaftars as $p)
                            <div style="border: 1px solid var(--glass-border); padding: 15px; border-radius: 8px; margin-bottom: 10px; background: var(--glass-bg);">
                                <p><strong>Nama:</strong> {{ $p->nama }}</p>
                                <p><strong>Blok / RT:</strong> {{ $p->blok_rumah }} / {{ $p->rt }}</p>
                                <p>
                                    <strong>Status:</strong> 
                                    @if($p->status_verifikasi == 'Disetujui')
                                        <span style="color: green; font-weight: bold;">✔ {{ $p->status_verifikasi }}</span>
                                        <div style="margin-top: 10px;">
                                            <a href="{{ url('/tiket/'.$p->id) }}" target="_blank" class="btn" style="background: var(--color-primary); color: white; padding: 5px 15px; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 0.9rem;">⬇ Download Kupon & Jadwal</a>
                                        </div>
                                    @elseif($p->status_verifikasi == 'Ditolak')
                                        <span style="color: red; font-weight: bold;">❌ {{ $p->status_verifikasi }}</span>
                                    @else
                                        <span style="color: orange; font-weight: bold;">⏳ {{ $p->status_verifikasi }}</span>
                                    @endif
                                </p>
                                @if($p->catatan_admin)
                                    <p><strong>Catatan Panitia:</strong> {{ $p->catatan_admin }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px;">
                            Pendaftaran dengan Nomor HP tersebut tidak ditemukan.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layout>

