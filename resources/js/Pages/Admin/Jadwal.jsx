import { useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function AdminJadwal({ jadwals, lombas }) {
    const { flash } = usePage().props;
    const { data, setData, post, put, delete: destroy, processing, reset } = useForm({
        id: '',
        kegiatan: '',
        waktu_mulai: '',
        waktu_selesai: '',
        lokasi: '',
        deskripsi: '',
        id_lomba: '',
        _method: 'POST'
    });
    
    const [isEdit, setIsEdit] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (isEdit) {
            post(`/admin/jadwal/${data.id}/update`, {
                onSuccess: () => resetForm()
            });
        } else {
            post('/admin/jadwal', {
                onSuccess: () => resetForm()
            });
        }
    };

    const handleEdit = (jadwal) => {
        setIsEdit(true);
        setData({
            id: jadwal.id,
            kegiatan: jadwal.kegiatan,
            waktu_mulai: jadwal.waktu_mulai.slice(0, 16),
            waktu_selesai: jadwal.waktu_selesai ? jadwal.waktu_selesai.slice(0, 16) : '',
            lokasi: jadwal.lokasi || '',
            deskripsi: jadwal.deskripsi || '',
            id_lomba: jadwal.id_lomba || '',
            _method: 'POST'
        });
    };

    const handleDelete = (id) => {
        if (confirm('Yakin ingin menghapus jadwal ini?')) {
            post(`/admin/jadwal/${id}/delete`);
        }
    };

    const resetForm = () => {
        setIsEdit(false);
        reset();
    };

    return (
        <AdminLayout title="Manajemen Jadwal - Admin">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
                <div>
                    <h1 style={{ marginBottom: '5px' }}>Manajemen Jadwal Acara & Lomba</h1>
                    <p style={{ opacity: 0.8 }}>Atur jadwal kegiatan secara fleksibel. Dapat dikaitkan dengan lomba atau sebagai kegiatan umum (misal: Pembukaan, Doa).</p>
                </div>
            </div>

            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '20px', alignItems: 'flex-start' }}>
                <div className="glass-card" style={{ padding: '20px', flex: '1 1 300px', width: '100%' }}>
                    <h3 style={{ marginBottom: '20px', borderBottom: '1px solid var(--glass-border)', paddingBottom: '10px' }}>
                        {isEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru'}
                    </h3>
                    <form onSubmit={handleSubmit}>
                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Nama Kegiatan</label>
                            <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} required value={data.kegiatan} onChange={e => setData('kegiatan', e.target.value)} />
                        </div>
                        
                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Kaitkan dengan Lomba (Opsional)</label>
                            <select className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.id_lomba} onChange={e => setData('id_lomba', e.target.value)}>
                                <option value="" style={{ color: '#000' }}>-- Bukan Lomba / Acara Umum --</option>
                                {lombas.map(l => (
                                    <option key={l.id} value={l.id} style={{ color: '#000' }}>{l.nama_lomba}</option>
                                ))}
                            </select>
                        </div>

                        <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '15px', marginBottom: '15px' }}>
                            <div style={{ flex: 1 }}>
                                <label style={{ display: 'block', marginBottom: '5px' }}>Waktu Mulai</label>
                                <input type="datetime-local" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} required value={data.waktu_mulai} onChange={e => setData('waktu_mulai', e.target.value)} />
                            </div>
                            <div style={{ flex: 1 }}>
                                <label style={{ display: 'block', marginBottom: '5px' }}>Waktu Selesai (Opsional)</label>
                                <input type="datetime-local" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.waktu_selesai} onChange={e => setData('waktu_selesai', e.target.value)} />
                            </div>
                        </div>

                        <div style={{ marginBottom: '15px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Lokasi</label>
                            <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.lokasi} onChange={e => setData('lokasi', e.target.value)} />
                        </div>

                        <div style={{ marginBottom: '20px' }}>
                            <label style={{ display: 'block', marginBottom: '5px' }}>Deskripsi</label>
                            <textarea className="form-control" rows="3" style={{ background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)', padding: '10px', borderRadius: '4px', width: '100%', boxSizing: 'border-box' }} value={data.deskripsi} onChange={e => setData('deskripsi', e.target.value)}></textarea>
                        </div>

                        <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '10px' }}>
                            <button type="submit" disabled={processing} className="btn btn-primary" style={{ flex: 1, opacity: processing ? 0.7 : 1 }}>
                                {isEdit ? 'Update Jadwal' : 'Simpan Jadwal'}
                            </button>
                            {isEdit && (
                                <button type="button" onClick={resetForm} className="btn" style={{ background: 'rgba(255,255,255,0.1)', color: '#fff' }}>Batal</button>
                            )}
                        </div>
                    </form>
                </div>

                <div className="glass-card" style={{ padding: '20px', flex: '2 1 500px', width: '100%', minWidth: 0 }}>
                    <h3>Daftar Jadwal</h3>
                    <div className="table-responsive">
                        <table style={{ marginTop: '15px' }}>
                            <thead>
                                <tr>
                                    <th>Kegiatan</th>
                                    <th>Terkait Lomba</th>
                                    <th>Waktu</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {jadwals.map((jadwal) => (
                                    <tr key={jadwal.id}>
                                        <td style={{ fontWeight: 'bold' }}>{jadwal.kegiatan}</td>
                                        <td>{jadwal.lomba ? jadwal.lomba.nama_lomba : '-'}</td>
                                        <td>
                                            {new Date(jadwal.waktu_mulai).toLocaleString('id-ID', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' })}
                                            {jadwal.waktu_selesai && ` - ${new Date(jadwal.waktu_selesai).toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit' })}`}
                                        </td>
                                        <td>{jadwal.lokasi}</td>
                                        <td>
                                            <button onClick={() => handleEdit(jadwal)} className="btn" style={{ padding: '5px 10px', background: '#ffc107', color: '#000', border: 'none', borderRadius: '4px', marginRight: '5px', fontSize: '0.8rem', cursor: 'pointer' }}>Edit</button>
                                            <button onClick={() => handleDelete(jadwal.id)} className="btn" style={{ padding: '5px 10px', background: '#dc3545', color: '#fff', border: 'none', borderRadius: '4px', fontSize: '0.8rem', cursor: 'pointer' }}>Hapus</button>
                                        </td>
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
