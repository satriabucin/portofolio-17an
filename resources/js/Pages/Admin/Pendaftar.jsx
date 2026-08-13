import { useForm, usePage, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Pendaftar({ pendaftars }) {
    const { flash } = usePage().props;

    useEffect(() => {
        const interval = setInterval(() => {
            router.reload({ only: ['pendaftars'], preserveScroll: true, preserveState: true });
        }, 5000); // 5 detik
        return () => clearInterval(interval);
    }, []);

    return (
        <AdminLayout title="Verifikasi Pendaftar - Admin">
            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ justifyContent: 'space-between', alignItems: 'flex-start', gap: '15px', marginBottom: '20px' }}>
                <div>
                    <h1 style={{ marginBottom: '5px' }}>Verifikasi Pendaftar</h1>
                    <p style={{ opacity: 0.8 }}>Daftar warga yang mendaftar lomba. Harap verifikasi kesesuaian data warga.</p>
                </div>
                <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '10px' }}>
                    <a href="/admin/export-excel" className="btn" style={{ background: '#28a745', color: 'white', padding: '10px 15px', borderRadius: '4px', textDecoration: 'none' }}>⬇ Export Excel</a>
                    <a href="/admin/export-pdf" className="btn" style={{ background: '#dc3545', color: 'white', padding: '10px 15px', borderRadius: '4px', textDecoration: 'none' }}>⬇ Export PDF</a>
                </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))', gap: '20px', maxHeight: '70vh', overflowY: 'auto', paddingRight: '10px', paddingBottom: '20px' }}>
                {pendaftars.length > 0 ? pendaftars.map(p => (
                    <PendaftarCard key={p.id} p={p} />
                )) : (
                    <div className="glass-card" style={{ gridColumn: '1 / -1', textAlign: 'center', padding: '50px', color: 'var(--color-text)', opacity: 0.7 }}>
                        Belum ada warga yang mendaftar.
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}

function PendaftarCard({ p }) {
    const { data: setujuiData, post: postSetujui, processing: setujuiProcessing } = useForm({
        status: 'Disetujui',
        action: 'setujui'
    });

    const { data: tolakData, setData: setTolakData, post: postTolak, processing: tolakProcessing } = useForm({
        status: 'Ditolak',
        catatan_admin: ''
    });

    const { data: batalData, post: postBatal, processing: batalProcessing } = useForm({
        status: 'Dibatalkan'
    });

    const handleSetujui = (action) => {
        setujuiData.action = action;
        postSetujui(`/admin/pendaftar/${p.id}`);
    };

    const handleTolak = (e) => {
        e.preventDefault();
        postTolak(`/admin/pendaftar/${p.id}`);
    };

    const handleBatal = (e) => {
        e.preventDefault();
        if(confirm('Yakin ingin membatalkan peserta ini? Mereka tidak akan diikutsertakan di lomba dan pesan pengingat WhatsApp Hari-H tidak akan dikirim.')) {
            postBatal(`/admin/pendaftar/${p.id}`);
        }
    };

    return (
        <div className="glass-card" style={{ overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
            <div style={{ padding: '20px', borderBottom: '1px solid var(--glass-border)' }}>
                <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ justifyContent: 'space-between', alignItems: 'flex-start', gap: '10px', marginBottom: '10px' }}>
                    <h3 style={{ margin: 0, fontSize: '1.2rem', color: 'var(--color-text)' }}>{p.nama}</h3>
                    {p.status_verifikasi === 'Menunggu Verifikasi' && <span style={{ color: '#856404', background: '#fff3cd', padding: '4px 8px', borderRadius: '20px', fontSize: '0.8rem', fontWeight: 'bold' }}>Menunggu</span>}
                    {p.status_verifikasi === 'Disetujui' && <span style={{ color: '#155724', background: '#d4edda', padding: '4px 8px', borderRadius: '20px', fontSize: '0.8rem', fontWeight: 'bold' }}>Disetujui</span>}
                    {p.status_verifikasi === 'Dibatalkan' && <span style={{ color: '#383d41', background: '#e2e3e5', padding: '4px 8px', borderRadius: '20px', fontSize: '0.8rem', fontWeight: 'bold' }}>Dibatalkan</span>}
                    {p.status_verifikasi === 'Ditolak' && <span style={{ color: '#721c24', background: '#f8d7da', padding: '4px 8px', borderRadius: '20px', fontSize: '0.8rem', fontWeight: 'bold' }}>Ditolak</span>}
                </div>
                <div style={{ fontSize: '0.85rem', color: 'var(--color-text)', opacity: 0.8 }}>
                    ⏱️ {new Date(p.created_at).toLocaleString('id-ID')}
                </div>
            </div>

            <div style={{ padding: '20px', flex: 1 }}>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '10px', fontSize: '0.95rem' }}>
                    <div style={{ background: 'var(--glass-bg)', padding: '10px', borderRadius: '6px', border: '1px solid var(--glass-border)' }}>
                        <strong style={{ display: 'block', fontSize: '0.8rem', color: 'var(--color-text)', opacity: 0.6, marginBottom: '2px' }}>Blok & RT</strong>
                        {p.blok_rumah} / {p.rt}
                    </div>
                    <div style={{ background: 'var(--glass-bg)', padding: '10px', borderRadius: '6px', border: '1px solid var(--glass-border)' }}>
                        <strong style={{ display: 'block', fontSize: '0.8rem', color: 'var(--color-text)', opacity: 0.6, marginBottom: '2px' }}>No Handphone</strong>
                        <a href={`https://wa.me/${p.no_hp.replace(/^0/, '62')}`} target="_blank" rel="noreferrer" style={{ color: '#25D366', fontWeight: 'bold' }}>📞 {p.no_hp}</a>
                    </div>
                    <div style={{ background: 'var(--glass-bg)', padding: '10px', borderRadius: '6px', gridColumn: '1 / -1', border: '1px solid var(--glass-border)' }}>
                        <strong style={{ display: 'block', fontSize: '0.8rem', color: 'var(--color-text)', opacity: 0.6, marginBottom: '5px' }}>Pilihan Lomba</strong>
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '5px' }}>
                            {p.lombas?.map(lomba => (
                                <span key={lomba.id} style={{ background: 'var(--color-primary)', color: 'white', padding: '3px 8px', borderRadius: '4px', fontSize: '0.8rem' }}>{lomba.nama_lomba}</span>
                            ))}
                        </div>
                    </div>
                </div>

                {p.catatan_admin && (
                    <div style={{ marginTop: '15px', background: 'rgba(255,193,7,0.1)', padding: '10px', borderRadius: '6px', fontSize: '0.9rem', borderLeft: '4px solid #ffc107' }}>
                        <strong>Catatan Penolakan:</strong><br />
                        {p.catatan_admin}
                    </div>
                )}
            </div>

            <div style={{ padding: '20px', background: 'var(--glass-bg)', borderTop: '1px solid var(--glass-border)' }}>
                {p.status_verifikasi === 'Menunggu Verifikasi' ? (
                    <>
                        <div style={{ marginBottom: '10px' }}>
                            <button onClick={() => handleSetujui('setujui')} disabled={setujuiProcessing} className="btn" style={{ width: '100%', marginBottom: '5px', background: 'rgba(40,167,69,0.2)', border: '1px solid rgba(40,167,69,0.5)', color: 'var(--color-text)', padding: '10px', fontSize: '0.95rem' }}>✔ Setujui Saja</button>
                            <button onClick={() => handleSetujui('setujui_wa')} disabled={setujuiProcessing} className="btn" style={{ width: '100%', background: '#25D366', border: '1px solid #128c7e', color: '#fff', padding: '10px', fontSize: '0.95rem' }}>📱 Setujui & Kirim Notif WA</button>
                        </div>
                        <form onSubmit={handleTolak}>
                            <input type="text" placeholder="Tulis alasan tolak di sini..." className="form-control" style={{ marginBottom: '10px', padding: '10px', fontSize: '0.9rem', borderColor: 'rgba(220,53,69,0.5)', background: 'var(--glass-bg)', color: 'var(--color-text)', width: '100%', boxSizing: 'border-box' }} value={tolakData.catatan_admin} onChange={e => setTolakData('catatan_admin', e.target.value)} />
                            <button type="submit" disabled={tolakProcessing} className="btn" style={{ width: '100%', background: 'rgba(220,53,69,0.2)', border: '1px solid rgba(220,53,69,0.5)', color: 'var(--color-text)', padding: '10px', fontSize: '0.95rem' }}>❌ Tolak Pendaftaran</button>
                        </form>
                    </>
                ) : p.status_verifikasi === 'Disetujui' ? (
                    <form onSubmit={handleBatal}>
                        <button type="submit" disabled={batalProcessing} className="btn" style={{ width: '100%', background: 'rgba(255,193,7,0.2)', border: '1px solid rgba(255,193,7,0.5)', color: 'var(--color-text)', padding: '10px', fontSize: '0.95rem' }}>🚫 Batalkan Peserta (Gajadi Ikut)</button>
                    </form>
                ) : (
                    <div style={{ textAlign: 'center', color: 'var(--color-text)', opacity: 0.7, fontStyle: 'italic', fontSize: '0.9rem' }}>
                        Tidak ada aksi yang tersedia.
                    </div>
                )}
            </div>
        </div>
    );
}
