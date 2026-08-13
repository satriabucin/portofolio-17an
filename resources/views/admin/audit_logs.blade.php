@extends('admin.layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h1 style="margin-bottom: 5px;">Audit Trail</h1>
        <p style="opacity: 0.8;">Rekam jejak seluruh aktivitas krusial di panel admin.</p>
    </div>
</div>

<div class="glass-card table-responsive" style="padding: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 15px; border-bottom: 1px solid var(--glass-border); text-align: left;">Waktu</th>
                <th style="padding: 15px; border-bottom: 1px solid var(--glass-border); text-align: left;">Aksi</th>
                <th style="padding: 15px; border-bottom: 1px solid var(--glass-border); text-align: left;">Deskripsi</th>
                <th style="padding: 15px; border-bottom: 1px solid var(--glass-border); text-align: left;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); font-weight: bold; color: var(--color-primary);">{{ $log->action }}</td>
                <td style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $log->description }}</td>
                <td style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: monospace;">{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
