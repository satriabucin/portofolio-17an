@extends('admin.layout')

@section('content')
<h1>Master Data Lomba</h1>
<p>Kelola daftar lomba yang tersedia untuk pendaftaran warga.</p>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">{{ session('success') }}</div>
@endif

<div class="glass-card" style="padding: 20px; flex: 1 1 300px; max-width: 400px; height: fit-content;">
    <h3 style="margin-bottom: 20px; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">Tambah Lomba Baru</h3>
    <form action="{{ url('/admin/lomba') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Nama Lomba</label>
            <input type="text" name="nama_lomba" class="form-control" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Kategori Usia</label>
            <select name="kategori_usia" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                <option value="" style="color: #333;">Umum</option>
                <option value="Anak-anak" style="color: #333;">Anak-anak</option>
                <option value="Remaja" style="color: #333;">Remaja</option>
                <option value="Dewasa" style="color: #333;">Dewasa</option>
            </select>
        </div>
        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px;">Kuota Peserta</label>
                <input type="number" name="kuota" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;" min="1" placeholder="Kosongkan jika tidak terbatas">
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px;">Jml Orang / Tim</label>
                <input type="number" name="jumlah_anggota_per_tim" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;" min="1" value="1" required placeholder="Regu > 1, Individu 1">
            </div>
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Jadwal Waktu</label>
            <input type="datetime-local" name="jadwal_waktu" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lapangan RT 01" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px;">Deskripsi Lomba</label>
            <textarea name="deskripsi" class="form-control" rows="3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Lomba</button>
    </form>
</div>

<!-- Right Side: Daftar Lomba -->
<div class="glass-card" style="padding: 20px; flex: 2 1 500px;">
    <h3>Daftar Lomba</h3>
    <div class="table-responsive">
    <table style="margin-top: 15px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lomba</th>
                <th>Kategori Usia</th>
                <th>Jadwal & Lokasi</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lombas as $lomba)
            <tr>
                <td>{{ $lomba->id }}</td>
                <td>{{ $lomba->nama_lomba }}</td>
                <td>{{ $lomba->kategori_usia }}</td>
                <td>
                    @if($lomba->jadwal_waktu)
                        <strong>{{ date('d M Y, H:i', strtotime($lomba->jadwal_waktu)) }}</strong><br>
                        <span style="font-size: 0.85em; opacity: 0.8;">{{ $lomba->lokasi }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $lomba->deskripsi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
