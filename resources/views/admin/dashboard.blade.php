@extends('admin.layout')

@section('content')
<h1>Dashboard Analitik</h1>
<p>Halo, {{ session('admin_name') }}. Selamat datang di panel admin.</p>

<div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 30px;">
    <div class="glass-card" style="padding: 20px; flex: 1; min-width: 250px;">
        <h3 style="margin-bottom: 10px;">Total Pendaftar</h3>
        <p style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">{{ $total_pendaftar }}</p>
    </div>
    <div class="glass-card" style="padding: 20px; flex: 1; min-width: 250px;">
        <h3 style="margin-bottom: 10px;">Total Lomba</h3>
        <p style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">{{ count($lombas) }}</p>
    </div>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 30px;">
    <div class="glass-card" style="padding: 20px; flex: 2; min-width: 300px;">
        <h3>Grafik Peminat Lomba</h3>
        <canvas id="barChart" style="margin-top: 15px; max-height: 300px;"></canvas>
    </div>
    <div class="glass-card" style="padding: 20px; flex: 1; min-width: 250px;">
        <h3>Status Verifikasi</h3>
        <canvas id="pieChart" style="margin-top: 15px; max-height: 300px;"></canvas>
    </div>
</div>

<div class="glass-card" style="padding: 20px; margin-top: 30px;">
    <h3>Peringkat Lomba Terpopuler</h3>
    <div class="table-responsive">
        <table style="margin-top: 15px;">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama Lomba</th>
                <th>Total Peserta Disetujui</th>
                <th>Status Kepadatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lombas as $index => $lomba)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $lomba->nama_lomba }}</td>
                <td>{{ $lomba->total_peserta }}</td>
                <td>
                    @if($index == 0 && $lomba->total_peserta > 0)
                        <span style="background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem;">Favorit 🔥</span>
                    @elseif($index == count($lombas) - 1 && $lomba->total_peserta == 0)
                        <span style="background: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem;">Sepi Peminat</span>
                    @else
                        Normal
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

<div class="glass-card" style="padding: 20px; margin-top: 30px;">
    <h3>Manajemen Semua Pendaftar (CRUD)</h3>
    <p style="opacity: 0.8; margin-bottom: 20px;">Klik nama pendaftar untuk melihat detail, mengubah, atau menghapus data mereka.</p>
    
    @if(session('success'))
        <div style="background: rgba(40,167,69,0.2); color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(40,167,69,0.3);">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table style="margin-top: 15px; width: 100%; border-collapse: collapse;">
        <thead style="position: sticky; top: 0; background: #1a1a2e; z-index: 10;">
            <tr>
                <th>No</th>
                <th>Nama Pendaftar</th>
                <th>Blok/RT</th>
                <th>No HP</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($all_pendaftars as $index => $pendaftar)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <a href="javascript:void(0)" data-pendaftar="{{ $pendaftar->toJson() }}" onclick="openPendaftarModal(JSON.parse(this.getAttribute('data-pendaftar')))" style="color: #4ade80; font-weight: bold; text-decoration: underline;">
                        {{ $pendaftar->nama }}
                    </a>
                </td>
                <td>{{ $pendaftar->blok_rumah }} / {{ $pendaftar->rt }}</td>
                <td>{{ $pendaftar->no_hp }}</td>
                <td>
                    @if($pendaftar->status_verifikasi == 'Menunggu Verifikasi')
                        <span style="color: #856404; background: #fff3cd; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">Menunggu</span>
                    @elseif($pendaftar->status_verifikasi == 'Disetujui')
                        <span style="color: #155724; background: #d4edda; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">Disetujui</span>
                    @elseif($pendaftar->status_verifikasi == 'Dibatalkan')
                        <span style="color: #383d41; background: #e2e3e5; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">Dibatalkan</span>
                    @else
                        <span style="color: #721c24; background: #f8d7da; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">Ditolak</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>

<div class="glass-card" style="padding: 20px; margin-top: 30px;">
    <h3>Riwayat Pendaftar Terverifikasi</h3>
    <p style="opacity: 0.8; margin-bottom: 20px;">Daftar warga yang pendaftarannya sudah Anda setujui atau tolak.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; max-height: 400px; overflow-y: auto; padding-right: 10px;">
        @forelse($verified_pendaftars as $vp)
        <div style="border: 1px solid var(--glass-border); border-radius: 8px; padding: 15px; display: flex; flex-direction: column; background: rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <h4 style="margin: 0; color: #fff;">{{ $vp->nama }}</h4>
                @if($vp->status_verifikasi == 'Disetujui')
                    <span style="color: #155724; background: #d4edda; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Disetujui</span>
                @else
                    <span style="color: #721c24; background: #f8d7da; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Ditolak</span>
                @endif
            </div>
            
            <div style="font-size: 0.85rem; color: #ccc; margin-bottom: 10px;">
                Blok/RT: {{ $vp->blok_rumah }} / {{ $vp->rt }}<br>
                Diperbarui: {{ $vp->updated_at->format('d M Y, H:i') }}
            </div>

            @if($vp->catatan_admin)
            <div style="font-size: 0.8rem; background: rgba(255,193,7,0.1); padding: 8px; border-radius: 4px; border-left: 3px solid #ffc107; margin-top: auto;">
                <strong>Catatan:</strong> {{ $vp->catatan_admin }}
            </div>
            @endif
        </div>
        @empty
        <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: #888;">
            Belum ada pendaftar yang diverifikasi.
        </div>
        @endforelse
    </div>
</div>

<!-- Chart.js CDN & Init -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const lombaData = @json($lombas);
    const labels = lombaData.map(l => l.nama_lomba);
    const data = lombaData.map(l => l.total_peserta);

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Peserta (Disetujui)',
                data: data,
                backgroundColor: 'rgba(255, 71, 71, 0.5)',
                borderColor: 'rgba(255, 71, 71, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Simulate Status Data (in a real app, query this via controller)
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Disetujui', 'Menunggu', 'Ditolak'],
            datasets: [{
                data: [{{ $total_disetujui }}, {{ $total_pendaftar - $total_disetujui }}, 0],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: { responsive: true }
    });
</script>

<!-- Modal CRUD Pendaftar -->
<div id="pendaftarModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div class="glass-card" style="width: 100%; max-width: 500px; padding: 30px; position: relative;">
        <button onclick="closePendaftarModal()" style="position: absolute; top: 15px; right: 20px; background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;">&times;</button>
        <h2 style="margin-bottom: 20px;">Detail & Edit Pendaftar</h2>
        
        <form id="editPendaftarForm" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: #ccc;">Nama Lengkap</label>
                <input type="text" id="modal_nama" name="nama" class="form-control" style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.2); color: #fff; padding: 10px;" required>
            </div>
            
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; color: #ccc;">Blok Rumah</label>
                    <input type="text" id="modal_blok" name="blok_rumah" class="form-control" style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.2); color: #fff; padding: 10px;" required>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; color: #ccc;">RT</label>
                    <input type="text" id="modal_rt" name="rt" class="form-control" style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.2); color: #fff; padding: 10px;" required>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; color: #ccc;">Nomor WhatsApp</label>
                <input type="text" id="modal_hp" name="no_hp" class="form-control" style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.2); color: #fff; padding: 10px;" required>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn" style="flex: 1; background: rgba(40,167,69,0.8); border:none; padding: 10px;">Simpan Perubahan</button>
            </div>
        </form>

        <form id="deletePendaftarForm" method="POST" style="margin-top: 10px;">
            @csrf
            <button type="submit" onclick="return confirm('Peringatan: Data pendaftar akan dihapus permanen! Yakin?')" class="btn" style="width: 100%; background: rgba(220,53,69,0.5); border: 1px solid rgba(220,53,69,0.8); padding: 10px;">Hapus Permanen</button>
        </form>
    </div>
</div>

<script>
function openPendaftarModal(pendaftar) {
    document.getElementById('pendaftarModal').style.display = 'flex';
    document.getElementById('modal_nama').value = pendaftar.nama;
    document.getElementById('modal_blok').value = pendaftar.blok_rumah;
    document.getElementById('modal_rt').value = pendaftar.rt;
    document.getElementById('modal_hp').value = pendaftar.no_hp;
    
    // Set form actions
    let baseUrl = "{{ url('/admin/pendaftar') }}";
    document.getElementById('editPendaftarForm').action = baseUrl + '/' + pendaftar.id + '/update';
    document.getElementById('deletePendaftarForm').action = baseUrl + '/' + pendaftar.id + '/delete';
}

function closePendaftarModal() {
    document.getElementById('pendaftarModal').style.display = 'none';
}
</script>
@endsection
