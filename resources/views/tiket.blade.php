<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Lomba 17-an</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center; color: #333; }
        .ticket-box { border: 2px dashed #ff4747; padding: 30px; margin: 0 auto; width: 80%; max-width: 600px; border-radius: 15px; }
        h1 { color: #ff4747; text-transform: uppercase; letter-spacing: 2px; }
        .details { margin: 20px 0; text-align: left; font-size: 18px; }
        .details strong { width: 150px; display: inline-block; }
        .qrcode { margin-top: 30px; }
        .footer { margin-top: 20px; font-size: 14px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <h1>KUPON & PENGINGAT JADWAL</h1>
        <h2>Peringatan HUT RI Ke-81</h2>
        <div class="details">
            <p><strong>Nama</strong>: {{ $pendaftar->nama }}</p>
            <p><strong>Alamat</strong>: {{ $pendaftar->blok_rumah }}, {{ $pendaftar->rt }}</p>
            
            <h3 style="margin-top: 30px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Jadwal Lomba Anda</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Lomba</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Tanggal & Jam</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftar->lombas as $l)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;"><strong>{{ $l->nama_lomba }}</strong></td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                {{ $l->jadwal_waktu ? date('d M Y, H:i', strtotime($l->jadwal_waktu)) : 'Menunggu Info' }}
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                {{ $l->lokasi ?: 'Menunggu Info' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="qrcode">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" width="200" height="200">
        </div>
        <p><strong>ID Pendaftaran: #{{ str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
        <div class="footer">
            Harap tunjukkan kupon ini (digital/cetak) ke panitia saat hari perlombaan sebagai bukti pendaftaran yang sah.
        </div>
    </div>
</body>
</html>
