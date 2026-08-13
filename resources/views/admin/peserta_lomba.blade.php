@extends('admin.layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
    <div>
        <h1 style="margin-bottom: 5px;">Daftar Peserta Per Lomba</h1>
        <p style="opacity: 0.8;">Rekapitulasi warga yang telah diverifikasi dan siap mengikuti lomba.</p>
    </div>
    <button onclick="window.print()" class="btn btn-accent" style="padding: 10px 15px; border-radius: 6px; border:none; cursor:pointer;">🖨️ Cetak Rekap</button>
</div>

<!-- Print Styles -->
<style>
    @media print {
        @page { size: A4; margin: 1.5cm; }
        body { background: #fff !important; color: #000 !important; overflow: visible !important; }
        .sidebar, .menu-toggle, button, form { display: none !important; }
        .content { padding: 0 !important; margin: 0 !important; width: 100% !important; }
        .admin-layout { display: block !important; width: 100% !important; }
        
        /* Reset table and containers */
        .print-area { position: static !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .glass-card { background: none !important; border: none !important; color: #000 !important; box-shadow: none !important; padding: 0 !important; margin: 0 0 30px 0 !important; page-break-inside: auto; }
        th, td, h2, h3, h4, div { color: #000 !important; border-color: #ccc !important; }
        .scroll-container, .table-responsive { max-height: none !important; overflow: visible !important; display: block !important; width: 100% !important; }
        table { background: transparent !important; width: 100% !important; min-width: auto !important; }
        thead tr { background: transparent !important; }
        
        /* Hide global header */
        .content > div:first-child { display: none !important; }
    }
    
    @media screen {
        .scroll-container { max-height: 500px; overflow-y: auto; padding-right: 10px; }
    }
</style>

<div class="print-area">
    @forelse($lombas as $lomba)
        <div id="lomba-card-{{ $lomba->id }}" class="glass-card lomba-card" style="padding: 25px; margin-bottom: 25px;">
            <div style="border-bottom: 2px solid var(--color-primary); padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0; color: #fff;">{{ $lomba->nama_lomba }}</h2>
                    <div style="color: #ccc; font-size: 0.9rem; margin-top: 5px;">Kategori: {{ $lomba->kategori_usia ?? 'Umum' }}</div>
                    @if($lomba->jumlah_anggota_per_tim > 1)
                        <div style="color: #ffc107; font-size: 0.85rem; font-weight: bold; margin-top: 3px;">Lomba Beregu ({{ $lomba->jumlah_anggota_per_tim }} Orang/Tim)</div>
                    @endif
                </div>
                
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button onclick="printLomba({{ $lomba->id }})" class="btn" style="padding: 10px 15px; font-size: 0.9rem; background: #17a2b8; color: #fff; border: none; font-weight: bold; cursor: pointer; border-radius: 6px;">🖨️ Cetak Lomba Ini</button>
                </div>
            </div>
                
                @if($lomba->pendaftars->count() > 0)
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <form action="{{ url('/admin/lomba/' . $lomba->id . '/randomize') }}" method="POST" style="display: flex; gap: 10px; align-items: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; position: relative; z-index: 50;">
                        @csrf
                        <label style="color: #ccc; font-size: 0.9rem;">Bagi Sesi/Tim:</label>
                        <input type="number" name="jumlah_sesi" min="1" max="{{ $lomba->pendaftars->count() }}" required class="form-control" style="width: 80px; padding: 5px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;" placeholder="Jml">
                        <button type="submit" onclick="return confirm('Acak sesi untuk lomba ini?')" class="btn" style="padding: 5px 10px; font-size: 0.9rem; background: #ffc107; color: #000; border: none; font-weight: bold; cursor: pointer; position: relative; z-index: 55;">🎲 Acak</button>
                    </form>
                    
                    <form action="{{ url('/admin/lomba/' . $lomba->id . '/reset-sesi') }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Reset sesi ke awal (Belum Dibagi)?')" class="btn" style="padding: 10px 15px; font-size: 0.9rem; background: rgba(220,53,69,0.8); color: #fff; border: none; font-weight: bold; cursor: pointer; border-radius: 6px;">🔄 Reset</button>
                    </form>
                </div>
                @endif

            @if($lomba->pendaftars->count() > 0)
                @php
                    $groupedPendaftars = $lomba->pendaftars->groupBy(function($item) {
                        return $item->pivot->sesi ?: 'Belum Dibagi';
                    });
                @endphp

                <div class="scroll-container">
                @foreach($groupedPendaftars as $sesi => $pesertas)
                    @if($sesi != 'Belum Dibagi')
                        <h4 style="margin-top: 20px; margin-bottom: 10px; color: var(--color-primary);">Kelompok / Sesi {{ $sesi }}</h4>
                    @endif
                    <div class="table-responsive" style="margin-bottom: 20px;">
                        <table style="width: 100%; border-collapse: collapse; color: #fff; background: rgba(0,0,0,0.2);">
                            <thead style="position: sticky; top: 0; background: #1a1a2e; z-index: 10;">
                                <tr style="background: rgba(255,255,255,0.05);">
                                    <th style="padding: 12px; border-bottom: 1px solid var(--glass-border); text-align: left; width: 50px;">No</th>
                                    @if($lomba->jumlah_anggota_per_tim > 1 && $sesi != 'Belum Dibagi')
                                        <th style="padding: 12px; border-bottom: 1px solid var(--glass-border); text-align: left;">Tim</th>
                                    @endif
                                    <th style="padding: 12px; border-bottom: 1px solid var(--glass-border); text-align: left;">Nama Peserta</th>
                                    <th style="padding: 12px; border-bottom: 1px solid var(--glass-border); text-align: left;">Blok & RT</th>
                                    <th style="padding: 12px; border-bottom: 1px solid var(--glass-border); text-align: left;">Catatan Panitia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertas as $index => $peserta)
                                    <tr>
                                        <td style="padding: 12px; border-bottom: 1px solid var(--glass-border);">{{ $index + 1 }}</td>
                                        @if($lomba->jumlah_anggota_per_tim > 1 && $sesi != 'Belum Dibagi')
                                            <td style="padding: 12px; border-bottom: 1px solid var(--glass-border); font-weight: bold; color: #ffc107;">
                                                {{ $peserta->pivot->tim ? 'Tim ' . $peserta->pivot->tim : '-' }}
                                            </td>
                                        @endif
                                        <td style="padding: 12px; border-bottom: 1px solid var(--glass-border); font-weight: bold;">{{ $peserta->nama }}</td>
                                        <td style="padding: 12px; border-bottom: 1px solid var(--glass-border); color: #ccc;">{{ $peserta->blok_rumah }} / {{ $peserta->rt }}</td>
                                        <td style="padding: 12px; border-bottom: 1px solid var(--glass-border);">
                                            <!-- Ruang kosong untuk dicoret/ditandai panitia saat hari H -->
                                            <div style="border-bottom: 1px dashed var(--glass-border); width: 100%; height: 20px;"></div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 6px; color: #aaa;">
                    Belum ada peserta yang mendaftar dan diverifikasi untuk lomba ini.
                </div>
            @endif
        </div>
    @empty
        <div class="glass-card" style="text-align: center; padding: 50px; color: #aaa;">
            Belum ada data lomba.
        </div>
    @endforelse
</div>

<script>
    function printLomba(lombaId) {
        const targetCard = document.getElementById('lomba-card-' + lombaId);
        if (!targetCard) return;
        
        const title = targetCard.querySelector('h2').innerText;
        const headerDiv = targetCard.querySelector('h2').parentElement;
        const metaNodes = Array.from(headerDiv.children).filter(el => el.tagName !== 'H2');
        let metaHtml = metaNodes.map(el => `<div class="meta-item">${el.innerText}</div>`).join('');
        
        let tablesHtml = '';
        const scrollContainer = targetCard.querySelector('.scroll-container');
        if (scrollContainer) {
            tablesHtml = scrollContainer.innerHTML;
        } else {
            const emptyState = targetCard.querySelector('div[style*="text-align: center"]');
            if (emptyState) tablesHtml = emptyState.outerHTML;
        }

        const printContents = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak - ${title}</title>
                <style>
                    @page { size: A4; margin: 1.5cm; }
                    body { 
                        font-family: Arial, sans-serif; 
                        color: #000; 
                        background: #fff;
                        margin: 0;
                        padding: 0;
                    }
                    h2 { margin-bottom: 5px; color: #000; font-size: 24px; text-align: center; }
                    .meta-container { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
                    .meta-item { font-size: 14px; color: #333; margin-top: 5px; }
                    h4 { margin-top: 25px; margin-bottom: 10px; color: #000; font-size: 16px; font-weight: bold; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
                    th, td { border: 1px solid #000 !important; padding: 10px; text-align: left; color: #000 !important; }
                    th { background-color: #e0e0e0 !important; font-weight: bold; }
                    
                    /* Override inline styles from original DOM */
                    * { 
                        max-height: none !important; 
                        overflow: visible !important; 
                        background-color: transparent; 
                    }
                    th { background-color: #e0e0e0 !important; }
                </style>
            </head>
            <body>
                <div class="meta-container">
                    <h2>${title}</h2>
                    ${metaHtml}
                </div>
                ${tablesHtml}
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContents);
        printWindow.document.close();
        
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 250);
    }
</script>
@endsection
