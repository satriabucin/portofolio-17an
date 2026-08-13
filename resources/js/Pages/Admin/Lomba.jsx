import { useForm, usePage } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Lomba({ lombas }) {
    const { flash } = usePage().props;
    const { data, setData, post, processing } = useForm({
        nama_lomba: '',
        kategori_usia: '',
        kuota: '',
        jumlah_anggota_per_tim: '1',
        jadwal_waktu: '',
        lokasi: '',
        deskripsi: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/lomba', {
            onSuccess: () => {
                setData({
                    nama_lomba: '',
                    kategori_usia: '',
                    kuota: '',
                    jumlah_anggota_per_tim: '1',
                    jadwal_waktu: '',
                    lokasi: '',
                    deskripsi: '',
                });
            }
        });
    };

    return (
        <AdminLayout title="Master Lomba - Admin">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
                <div>
                    <h1 style={{ marginBottom: '5px' }}>Master Data Lomba</h1>
                    <p style={{ opacity: 0.8 }}>Kelola daftar lomba yang tersedia untuk pendaftaran warga.</p>
                </div>
            </div>

            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '20px', alignItems: 'flex-start' }}>
                <div className="glass-card" style={{ padding: '20px', flex: '1 1 300px', width: '100%' }}>
                    <h3 style={{ marginBottom: '20px', borderBottom: '1px solid var(--glass-border)', paddingBottom: '10px' }}>Tambah Lomba Baru</h3>
                    <form onSubmit={handleSubmit}>
                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Nama Lomba</label>
                            <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} required value={data.nama_lomba} onChange={e => setData('nama_lomba', e.target.value)} />
                        </div>
                        
                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Kategori Usia</label>
                            <select className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.kategori_usia} onChange={e => setData('kategori_usia', e.target.value)}>
                                <option value="" style={{ color: '#000' }}>Umum</option>
                                <option value="Anak-anak" style={{ color: '#000' }}>Anak-anak</option>
                                <option value="Remaja" style={{ color: '#000' }}>Remaja</option>
                                <option value="Dewasa" style={{ color: '#000' }}>Dewasa</option>
                            </select>
                        </div>

                        <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '15px', marginBottom: '15px' }}>
                            <div style={{ flex: 1 }}>
                                <label style={{ display: 'block', marginBottom: '5px' }}>Kuota Peserta</label>
                                <input type="number" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} min="1" placeholder="Kosongkan jika tidak terbatas" value={data.kuota} onChange={e => setData('kuota', e.target.value)} />
                            </div>
                            <div style={{ flex: 1 }}>
                                <label style={{ display: 'block', marginBottom: '5px' }}>Jml Orang / Tim</label>
                                <input type="number" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} min="1" required placeholder="Regu > 1, Individu 1" value={data.jumlah_anggota_per_tim} onChange={e => setData('jumlah_anggota_per_tim', e.target.value)} />
                            </div>
                        </div>

                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Jadwal Waktu</label>
                            <input type="datetime-local" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.jadwal_waktu} onChange={e => setData('jadwal_waktu', e.target.value)} />
                        </div>

                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Lokasi</label>
                            <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} placeholder="Contoh: Lapangan RT 01" value={data.lokasi} onChange={e => setData('lokasi', e.target.value)} />
                        </div>

                        <div style={{ marginBottom: '20px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Deskripsi Lomba</label>
                            <textarea className="form-control" rows="3" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.deskripsi} onChange={e => setData('deskripsi', e.target.value)}></textarea>
                        </div>

                        <button type="submit" disabled={processing} className="btn btn-primary" style={{ width: '100%', opacity: processing ? 0.7 : 1 }}>
                            {processing ? 'Menyimpan...' : 'Simpan Lomba'}
                        </button>
                    </form>
                </div>

                <div className="glass-card" style={{ padding: '20px', flex: '2 1 500px', width: '100%', minWidth: 0 }}>
                    <h3>Daftar Lomba</h3>
                    <div className="table-responsive">
                        <table style={{ marginTop: '15px' }}>
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
                                {lombas.map((lomba) => (
                                    <tr key={lomba.id}>
                                        <td>{lomba.id}</td>
                                        <td>{lomba.nama_lomba}</td>
                                        <td>{lomba.kategori_usia || 'Umum'}</td>
                                        <td>
                                            {lomba.jadwal_waktu ? (
                                                <>
                                                    <strong>{new Date(lomba.jadwal_waktu).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</strong><br/>
                                                    <span style={{ fontSize: '0.85em', opacity: 0.8 }}>{lomba.lokasi}</span>
                                                </>
                                            ) : '-'}
                                        </td>
                                        <td>{lomba.deskripsi}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
