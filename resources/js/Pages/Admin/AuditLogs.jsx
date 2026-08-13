import AdminLayout from '../../Layouts/AdminLayout';
import { Link } from '@inertiajs/react';

export default function AuditLogs({ logs }) {
    return (
        <AdminLayout title="Audit Trail - Admin">
            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ justifyContent: 'space-between', alignItems: 'flex-start', gap: '15px', marginBottom: '20px' }}>
                <div>
                    <h1 style={{ marginBottom: '5px' }}>Audit Trail</h1>
                    <p style={{ opacity: 0.8 }}>Rekam jejak seluruh aktivitas krusial di panel admin.</p>
                </div>
            </div>

            <div className="glass-card table-responsive" style={{ padding: '20px' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead>
                        <tr>
                            <th style={{ padding: '15px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Waktu</th>
                            <th style={{ padding: '15px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Aksi</th>
                            <th style={{ padding: '15px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Deskripsi</th>
                            <th style={{ padding: '15px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        {logs.data.map((log) => (
                            <tr key={log.id}>
                                <td style={{ padding: '15px', borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                                    {new Date(log.created_at).toLocaleString('id-ID')}
                                </td>
                                <td style={{ padding: '15px', borderBottom: '1px solid rgba(255,255,255,0.05)', fontWeight: 'bold', color: 'var(--color-primary)' }}>
                                    {log.action}
                                </td>
                                <td style={{ padding: '15px', borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                                    {log.description}
                                </td>
                                <td style={{ padding: '15px', borderBottom: '1px solid rgba(255,255,255,0.05)', fontFamily: 'monospace' }}>
                                    {log.ip_address}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                
                <div style={{ marginTop: '20px', display: 'flex', gap: '5px', flexWrap: 'wrap' }}>
                    {logs.links.map((link, i) => (
                        link.url ? (
                            <Link 
                                key={i} 
                                href={link.url} 
                                style={{ padding: '8px 12px', background: link.active ? 'var(--color-primary)' : 'var(--glass-bg)', color: link.active ? '#fff' : 'var(--color-text)', textDecoration: 'none', borderRadius: '4px', border: '1px solid var(--glass-border)' }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span 
                                key={i} 
                                style={{ padding: '8px 12px', background: 'rgba(255,255,255,0.05)', color: '#888', borderRadius: '4px', border: '1px solid var(--glass-border)' }}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        )
                    ))}
                </div>
            </div>
        </AdminLayout>
    );
}
