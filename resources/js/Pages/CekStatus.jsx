import { useForm, Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';

export default function CekStatus({ pendaftars }) {
    const { data, setData, post, processing } = useForm({
        no_hp: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/cek-status');
    };

    return (
        <PublicLayout title="Cek Status Pendaftaran">
            <div className="container section" style={{ paddingTop: '100px', paddingBottom: '100px' }}>
                <div className="glass-card" style={{ padding: '40px', maxWidth: '600px', margin: '0 auto' }}>
                    <h1 className="text-center mb-1">Cek Status Pendaftaran</h1>
                    <p className="text-center mb-4" style={{ opacity: 0.8 }}>Masukkan Nomor HP yang Anda gunakan saat mendaftar lomba.</p>

                    <form onSubmit={handleSubmit}>
                        <div className="form-group" style={{ display: 'flex', gap: '10px' }}>
                            <input 
                                type="tel" 
                                className="form-control" 
                                style={{ background: 'var(--glass-bg)', color: 'var(--color-text)', border: '1px solid var(--glass-border)', padding: '10px', borderRadius: '4px', flex: 1 }}
                                required 
                                placeholder="08xxxxxxxxxx"
                                value={data.no_hp}
                                onChange={e => setData('no_hp', e.target.value)}
                            />
                            <button type="submit" disabled={processing} className="btn btn-accent" style={{ opacity: processing ? 0.7 : 1 }}>
                                {processing ? 'Mencari...' : 'Cari'}
                            </button>
                        </div>
                    </form>

                    {pendaftars !== undefined && (
                        <div style={{ marginTop: '30px' }}>
                            {pendaftars.length > 0 ? (
                                <>
                                    <h3 style={{ marginBottom: '15px' }}>Hasil Pencarian:</h3>
                                    {pendaftars.map((p, index) => (
                                        <div key={index} style={{ border: '1px solid var(--glass-border)', padding: '15px', borderRadius: '8px', marginBottom: '10px', background: 'var(--glass-bg)' }}>
                                            <p><strong>Nama:</strong> {p.nama}</p>
                                            <p><strong>Blok / RT:</strong> {p.blok_rumah} / {p.rt}</p>
                                            <p>
                                                <strong>Status:</strong>{' '}
                                                {p.status_verifikasi === 'Disetujui' ? (
                                                    <span style={{ color: 'green', fontWeight: 'bold' }}>✔ {p.status_verifikasi}</span>
                                                ) : p.status_verifikasi === 'Ditolak' ? (
                                                    <span style={{ color: 'red', fontWeight: 'bold' }}>❌ {p.status_verifikasi}</span>
                                                ) : (
                                                    <span style={{ color: 'orange', fontWeight: 'bold' }}>⏳ {p.status_verifikasi}</span>
                                                )}
                                            </p>
                                            {p.status_verifikasi === 'Disetujui' && (
                                                <div style={{ marginTop: '10px' }}>
                                                    <a href={`/tiket/${p.id}`} target="_blank" rel="noreferrer" className="btn" style={{ background: 'var(--color-primary)', color: 'white', padding: '5px 15px', textDecoration: 'none', borderRadius: '4px', display: 'inline-block', fontSize: '0.9rem' }}>
                                                        ⬇ Download Kupon & Jadwal
                                                    </a>
                                                </div>
                                            )}
                                            {p.catatan_admin && (
                                                <p><strong>Catatan Panitia:</strong> {p.catatan_admin}</p>
                                            )}
                                        </div>
                                    ))}
                                </>
                            ) : (
                                <div style={{ background: '#f8d7da', color: '#721c24', padding: '15px', borderRadius: '8px' }}>
                                    Pendaftaran dengan Nomor HP tersebut tidak ditemukan.
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </PublicLayout>
    );
}
