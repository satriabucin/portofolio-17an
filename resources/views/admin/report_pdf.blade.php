<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pendaftar</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Rekap Data Pendaftar Lomba 17 Agustus</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Blok/RT</th>
                <th>No HP</th>
                <th>Lomba Diikuti</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftars as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->blok_rumah }} / {{ $p->rt }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>
                    @foreach($p->lombas as $l)
                        {{ $l->nama_lomba }}<br>
                    @endforeach
                </td>
                <td>{{ $p->status_verifikasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
