@extends('admin.layout')

@section('content')



<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
    <div>
        <h1 style="margin-bottom: 5px;">Verifikasi Pendaftar</h1>
        <p style="opacity: 0.8;">Daftar warga yang mendaftar lomba. Harap verifikasi kesesuaian data warga.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ url('/admin/export-excel') }}" class="btn" style="background: #28a745; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none;">⬇ Export Excel</a>
        <a href="{{ url('/admin/export-pdf') }}" class="btn" style="background: #dc3545; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none;">⬇ Export PDF</a>
    </div>
</div>

@if(session('success'))
    <div style="background: rgba(40,167,69,0.2); color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(40,167,69,0.3);">
        {!! session('success') !!}
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; max-height: 70vh; overflow-y: auto; padding-right: 10px; padding-bottom: 20px;">
    @forelse($pendaftars as $p)
    <div class="glass-card" style="overflow: hidden; display: flex; flex-direction: column;">
        
        <!-- Header Card -->
        <div style="padding: 20px; border-bottom: 1px solid var(--glass-border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <h3 style="margin: 0; font-size: 1.2rem; color: #fff;">{{ $p->nama }}</h3>
                @if($p->status_verifikasi == 'Menunggu Verifikasi')
                    <span style="color: #856404; background: #fff3cd; padding: 4px 8px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Menunggu</span>
                @elseif($p->status_verifikasi == 'Disetujui')
                    <span style="color: #155724; background: #d4edda; padding: 4px 8px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Disetujui</span>
                @elseif($p->status_verifikasi == 'Dibatalkan')
                    <span style="color: #383d41; background: #e2e3e5; padding: 4px 8px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Dibatalkan</span>
                @else
                    <span style="color: #721c24; background: #f8d7da; padding: 4px 8px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Ditolak</span>
                @endif
            </div>
            <div style="font-size: 0.85rem; color: #ccc;">
                ⏱️ {{ $p->created_at->format('d M Y - H:i') }}
            </div>
        </div>

        <!-- Body Card -->
        <div style="padding: 20px; flex: 1;">
            <div style="display: grid; grid-template-columns: 1fr; gap: 10px; font-size: 0.95rem;">
                <div style="background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; border: 1px solid var(--glass-border);">
                    <strong style="display: block; font-size: 0.8rem; color: #aaa; margin-bottom: 2px;">Blok & RT</strong>
                    {{ $p->blok_rumah }} / {{ $p->rt }}
                </div>
                <div style="background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; border: 1px solid var(--glass-border);">
                    <strong style="display: block; font-size: 0.8rem; color: #aaa; margin-bottom: 2px;">No Handphone</strong>
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $p->no_hp) }}" target="_blank" style="color: #25D366; font-weight: bold;">📞 {{ $p->no_hp }}</a>
                </div>
                <div style="background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; grid-column: 1 / -1; border: 1px solid var(--glass-border);">
                    <strong style="display: block; font-size: 0.8rem; color: #aaa; margin-bottom: 5px;">Pilihan Lomba</strong>
                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                        @foreach($p->lombas as $lomba)
                            <span style="background: var(--color-primary); color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">{{ $lomba->nama_lomba }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($p->catatan_admin)
            <div style="margin-top: 15px; background: rgba(255,193,7,0.1); padding: 10px; border-radius: 6px; font-size: 0.9rem; border-left: 4px solid #ffc107;">
                <strong>Catatan Penolakan:</strong><br>
                {{ $p->catatan_admin }}
            </div>
            @endif
        </div>

        <!-- Footer Actions Card -->
        <div style="padding: 20px; background: rgba(0,0,0,0.1); border-top: 1px solid var(--glass-border);">
            @if($p->status_verifikasi == 'Menunggu Verifikasi')
                <form action="{{ url('/admin/pendaftar/' . $p->id) }}" method="POST" style="margin-bottom: 10px;">
                    @csrf
                    <input type="hidden" name="status" value="Disetujui">
                    <button type="submit" name="action" value="setujui" class="btn" style="width: 100%; margin-bottom: 5px; background: rgba(40,167,69,0.2); border: 1px solid rgba(40,167,69,0.5); color: #fff; padding: 10px; font-size: 0.95rem;">✔ Setujui Saja</button>
                    <button type="submit" name="action" value="setujui_wa" class="btn" style="width: 100%; background: #25D366; border: 1px solid #128c7e; color: #fff; padding: 10px; font-size: 0.95rem;">📱 Setujui & Kirim Notif WA</button>
                </form>

                <form action="{{ url('/admin/pendaftar/' . $p->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Ditolak">
                    <input type="text" name="catatan_admin" placeholder="Tulis alasan tolak di sini..." class="form-control" style="margin-bottom: 10px; padding: 10px; font-size: 0.9rem; border-color: rgba(220,53,69,0.5); background: rgba(0,0,0,0.3); color: #fff;">
                    <button type="submit" class="btn" style="width: 100%; background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #fff; padding: 10px; font-size: 0.95rem;">❌ Tolak Pendaftaran</button>
                </form>
            @elseif($p->status_verifikasi == 'Disetujui')
                <form action="{{ url('/admin/pendaftar/' . $p->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Dibatalkan">
                    <button type="submit" onclick="return confirm('Yakin ingin membatalkan peserta ini? Mereka tidak akan diikutsertakan di lomba dan pesan pengingat WhatsApp Hari-H tidak akan dikirim.')" class="btn" style="width: 100%; background: rgba(255,193,7,0.2); border: 1px solid rgba(255,193,7,0.5); color: #fff; padding: 10px; font-size: 0.95rem;">🚫 Batalkan Peserta (Gajadi Ikut)</button>
                </form>
            @else
                <div style="text-align: center; color: #aaa; font-style: italic; font-size: 0.9rem;">
                    Tidak ada aksi yang tersedia.
                </div>
            @endif
        </div>

    </div>
    @empty
    <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #aaa;">
        Belum ada warga yang mendaftar.
    </div>
    @endforelse
</div>
@endsection
