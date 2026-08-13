import { Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';

export default function Jadwal({ jadwals }) {
    return (
        <PublicLayout title="Jadwal Lomba">
            <style>{`
                .timeline { position: relative; max-width: 800px; margin: 0 auto; padding: 40px 0; }
                .timeline::after { content: ''; position: absolute; width: 4px; background: var(--color-primary); top: 0; bottom: 0; left: 50%; margin-left: -2px; border-radius: 2px; }
                .timeline-item { padding: 10px 40px; position: relative; background: inherit; width: 50%; box-sizing: border-box; }
                .timeline-item.left { left: 0; }
                .timeline-item.right { left: 50%; }
                .timeline-item::after { content: ''; position: absolute; width: 20px; height: 20px; right: -10px; background-color: var(--color-background); border: 4px solid var(--color-primary); top: 15px; border-radius: 50%; z-index: 1; }
                .timeline-item.right::after { left: -10px; }
                .content { padding: 20px 30px; background: var(--glass-bg); position: relative; border-radius: 8px; border: 1px solid var(--glass-border); }
                
                @media screen and (max-width: 600px) {
                    .timeline::after { left: 31px; }
                    .timeline-item { width: 100%; padding-left: 70px; padding-right: 25px; }
                    .timeline-item.right { left: 0; }
                    .timeline-item.left::after, .timeline-item.right::after { left: 19px; }
                }
            `}</style>
            
            <div className="container" style={{ paddingTop: '100px', paddingBottom: '100px', position: 'relative' }}>
                <Link href="/" className="btn btn-glow" style={{ position: 'absolute', top: '40px', left: '20px', padding: '8px 16px', background: 'rgba(255, 255, 255, 0.1)', color: 'var(--color-text)', borderRadius: '8px', textDecoration: 'none', border: '1px solid var(--glass-border)', display: 'inline-flex', alignItems: 'center', gap: '8px', fontSize: '0.9rem' }}>
                    &larr; Kembali ke Beranda
                </Link>
                <div style={{ textAlign: 'center', marginBottom: '50px', marginTop: '40px' }}>
                    <h1 style={{ color: 'var(--color-primary)' }}>Susunan Acara & Jadwal Lomba</h1>
                    <p style={{ opacity: 0.8, maxWidth: '600px', margin: '0 auto' }}>Ikuti rangkaian kemeriahan acara 17 Agustus. Pastikan Anda hadir tepat waktu untuk setiap kegiatan yang telah dijadwalkan.</p>
                </div>

                <div className="timeline">
                    {jadwals.map((jadwal, index) => {
                        const isLeft = index % 2 === 0;
                        const startDate = new Date(jadwal.waktu_mulai);
                        const endDate = jadwal.waktu_selesai ? new Date(jadwal.waktu_selesai) : null;
                        
                        return (
                            <div key={jadwal.id} className={`timeline-item ${isLeft ? 'left' : 'right'}`}>
                                <div className="content">
                                    <h3 style={{ color: 'var(--color-primary)', marginBottom: '5px' }}>{jadwal.kegiatan}</h3>
                                    {jadwal.lomba && (
                                        <span style={{ display: 'inline-block', background: 'rgba(255, 71, 71, 0.2)', color: '#ff4747', padding: '3px 8px', borderRadius: '4px', fontSize: '0.75rem', fontWeight: 'bold', marginBottom: '10px' }}>
                                            🏅 {jadwal.lomba.nama_lomba}
                                        </span>
                                    )}
                                    <div style={{ fontSize: '0.9rem', color: 'var(--color-text)', opacity: 0.75, marginBottom: '10px' }}>
                                        📅 {startDate.toLocaleString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })} <br/>
                                        ⏰ {startDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} {endDate ? `- ${endDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}` : ''}
                                        {jadwal.lokasi && <><br/>📍 {jadwal.lokasi}</>}
                                    </div>
                                    <p style={{ margin: 0, opacity: 0.9 }}>{jadwal.deskripsi}</p>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </PublicLayout>
    );
}
