import { useEffect, useRef, useState } from 'react';
import { usePage, useForm } from '@inertiajs/react';
import Chart from 'chart.js/auto';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Dashboard({ total_pendaftar, total_disetujui, lombas, verified_pendaftars, all_pendaftars }) {
    const { auth, flash } = usePage().props;
    const barChartRef = useRef(null);
    const pieChartRef = useRef(null);
    
    // Modal State
    const [modalOpen, setModalOpen] = useState(false);
    const { data, setData, post, processing } = useForm({
        id: '',
        nama: '',
        blok_rumah: '',
        rt: '',
        no_hp: '',
        _method: 'POST'
    });

    useEffect(() => {
        let barChartInstance = null;
        let pieChartInstance = null;

        if (barChartRef.current && pieChartRef.current) {
            const labels = lombas.map(l => l.nama_lomba);
            const chartData = lombas.map(l => l.total_peserta);

            barChartInstance = new Chart(barChartRef.current, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Peserta (Disetujui)',
                        data: chartData,
                        backgroundColor: 'rgba(255, 71, 71, 0.5)',
                        borderColor: 'rgba(255, 71, 71, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            pieChartInstance = new Chart(pieChartRef.current, {
                type: 'pie',
                data: {
                    labels: ['Disetujui', 'Menunggu', 'Ditolak'],
                    datasets: [{
                        data: [total_disetujui, total_pendaftar - total_disetujui, 0],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: { responsive: true }
            });
        }

        return () => {
            if (barChartInstance) barChartInstance.destroy();
            if (pieChartInstance) pieChartInstance.destroy();
        };
    }, [lombas, total_disetujui, total_pendaftar]);

    const openModal = (p) => {
        setData({
            id: p.id,
            nama: p.nama,
            blok_rumah: p.blok_rumah,
            rt: p.rt,
            no_hp: p.no_hp,
            _method: 'POST'
        });
        setModalOpen(true);
    };

    const handleUpdate = (e) => {
        e.preventDefault();
        post(`/admin/pendaftar/${data.id}/update`, {
            onSuccess: () => setModalOpen(false)
        });
    };

    const handleDelete = (e) => {
        e.preventDefault();
        if (confirm('Peringatan: Data pendaftar akan dihapus permanen! Yakin?')) {
            post(`/admin/pendaftar/${data.id}/delete`, {
                onSuccess: () => setModalOpen(false)
            });
        }
    };

    return (
        <AdminLayout title="Dashboard Admin">
            <h1>Dashboard Analitik</h1>
            <p>Halo, {auth?.admin_name || 'Admin'}. Selamat datang di panel admin.</p>

            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '20px', marginTop: '30px' }}>
                <div className="glass-card" style={{ padding: '20px', flex: 1, minWidth: 0 }}>
                    <h3 style={{ marginBottom: '10px' }}>Total Pendaftar</h3>
                    <p style={{ fontSize: '2rem', fontWeight: 'bold', color: 'var(--color-primary)' }}>{total_pendaftar}</p>
                </div>
                <div className="glass-card" style={{ padding: '20px', flex: 1, minWidth: 0 }}>
                    <h3 style={{ marginBottom: '10px' }}>Total Lomba</h3>
                    <p style={{ fontSize: '2rem', fontWeight: 'bold', color: 'var(--color-primary)' }}>{lombas.length}</p>
                </div>
            </div>

            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '20px', marginTop: '30px' }}>
                <div className="glass-card" style={{ padding: '20px', flex: 2, minWidth: 0 }}>
                    <h3>Grafik Peminat Lomba</h3>
                    <div style={{ width: '100%', overflowX: 'auto' }}>
                        <canvas ref={barChartRef} style={{ marginTop: '15px', maxHeight: '300px' }}></canvas>
                    </div>
                </div>
                <div className="glass-card" style={{ padding: '20px', flex: 1, minWidth: 0 }}>
                    <h3>Status Verifikasi</h3>
                    <div style={{ width: '100%', overflowX: 'auto' }}>
                        <canvas ref={pieChartRef} style={{ marginTop: '15px', maxHeight: '300px' }}></canvas>
                    </div>
                </div>
            </div>

            <div className="glass-card" style={{ padding: '20px', marginTop: '30px', minWidth: 0 }}>
                <h3>Peringkat Lomba Terpopuler</h3>
                <div className="table-responsive">
                    <table style={{ marginTop: '15px' }}>
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Lomba</th>
                                <th>Total Peserta Disetujui</th>
                                <th>Status Kepadatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {lombas.map((lomba, index) => (
                                <tr key={lomba.id || index}>
                                    <td>{index + 1}</td>
                                    <td>{lomba.nama_lomba}</td>
                                    <td>{lomba.total_peserta}</td>
                                    <td>
                                        {index === 0 && lomba.total_peserta > 0 ? (
                                            <span style={{ background: '#d4edda', color: '#155724', padding: '5px 10px', borderRadius: '4px', fontSize: '0.8rem' }}>Favorit 🔥</span>
                                        ) : index === lombas.length - 1 && lomba.total_peserta === 0 ? (
                                            <span style={{ background: '#f8d7da', color: '#721c24', padding: '5px 10px', borderRadius: '4px', fontSize: '0.8rem' }}>Sepi Peminat</span>
                                        ) : 'Normal'}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="glass-card" style={{ padding: '20px', marginTop: '30px', minWidth: 0 }}>
                <h3>Manajemen Semua Pendaftar (CRUD)</h3>
                <p style={{ opacity: 0.8, marginBottom: '20px' }}>Klik nama pendaftar untuk melihat detail, mengubah, atau menghapus data mereka.</p>

                <div className="table-responsive" style={{ maxHeight: '400px', overflowY: 'auto' }}>
                    <table style={{ marginTop: '15px', width: '100%', borderCollapse: 'collapse' }}>
                        <thead style={{ position: 'sticky', top: 0, background: '#1a1a2e', zIndex: 10 }}>
                            <tr>
                                <th>No</th>
                                <th>Nama Pendaftar</th>
                                <th>Blok/RT</th>
                                <th>No HP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {all_pendaftars.map((pendaftar, index) => (
                                <tr key={pendaftar.id}>
                                    <td>{index + 1}</td>
                                    <td>
                                        <button onClick={() => openModal(pendaftar)} style={{ background: 'none', border: 'none', color: '#4ade80', fontWeight: 'bold', textDecoration: 'underline', cursor: 'pointer', padding: 0 }}>
                                            {pendaftar.nama}
                                        </button>
                                    </td>
                                    <td>{pendaftar.blok_rumah} / {pendaftar.rt}</td>
                                    <td>{pendaftar.no_hp}</td>
                                    <td>
                                        {pendaftar.status_verifikasi === 'Menunggu Verifikasi' ? (
                                            <span style={{ color: '#856404', background: '#fff3cd', padding: '2px 6px', borderRadius: '4px', fontSize: '0.75rem' }}>Menunggu</span>
                                        ) : pendaftar.status_verifikasi === 'Disetujui' ? (
                                            <span style={{ color: '#155724', background: '#d4edda', padding: '2px 6px', borderRadius: '4px', fontSize: '0.75rem' }}>Disetujui</span>
                                        ) : pendaftar.status_verifikasi === 'Dibatalkan' ? (
                                            <span style={{ color: '#383d41', background: '#e2e3e5', padding: '2px 6px', borderRadius: '4px', fontSize: '0.75rem' }}>Dibatalkan</span>
                                        ) : (
                                            <span style={{ color: '#721c24', background: '#f8d7da', padding: '2px 6px', borderRadius: '4px', fontSize: '0.75rem' }}>Ditolak</span>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            {modalOpen && (
                <div style={{ position: 'fixed', top: 0, left: 0, width: '100%', height: '100%', background: 'rgba(0,0,0,0.8)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '20px' }}>
                    <div className="glass-card" style={{ width: '100%', maxWidth: '500px', padding: '30px', position: 'relative' }}>
                        <button onClick={() => setModalOpen(false)} style={{ position: 'absolute', top: '15px', right: '20px', background: 'none', border: 'none', color: 'var(--color-text)', fontSize: '1.5rem', cursor: 'pointer' }}>&times;</button>
                        <h2 style={{ marginBottom: '20px' }}>Detail & Edit Pendaftar</h2>
                        
                        <form onSubmit={handleUpdate}>
                            <div style={{ marginBottom: '15px' }}>
                                <label style={{ display: 'block', marginBottom: '5px', color: 'var(--color-text)' }}>Nama Lengkap</label>
                                <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', borderColor: 'var(--glass-border)', color: 'var(--color-text)', padding: '10px' }} value={data.nama} onChange={e => setData('nama', e.target.value)} required />
                            </div>
                            
                            <div style={{ display: 'flex', gap: '15px', marginBottom: '15px' }}>
                                <div style={{ flex: 1 }}>
                                    <label style={{ display: 'block', marginBottom: '5px', color: 'var(--color-text)' }}>Blok Rumah</label>
                                    <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', borderColor: 'var(--glass-border)', color: 'var(--color-text)', padding: '10px' }} value={data.blok_rumah} onChange={e => setData('blok_rumah', e.target.value)} required />
                                </div>
                                <div style={{ flex: 1 }}>
                                    <label style={{ display: 'block', marginBottom: '5px', color: 'var(--color-text)' }}>RT</label>
                                    <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', borderColor: 'var(--glass-border)', color: 'var(--color-text)', padding: '10px' }} value={data.rt} onChange={e => setData('rt', e.target.value)} required />
                                </div>
                            </div>
                            
                            <div style={{ marginBottom: '20px' }}>
                                <label style={{ display: 'block', marginBottom: '5px', color: 'var(--color-text)' }}>Nomor WhatsApp</label>
                                <input type="text" className="form-control" style={{ background: 'var(--glass-bg)', borderColor: 'var(--glass-border)', color: 'var(--color-text)', padding: '10px' }} value={data.no_hp} onChange={e => setData('no_hp', e.target.value)} required />
                            </div>
                            
                            <div style={{ display: 'flex', gap: '10px', marginTop: '30px' }}>
                                <button type="submit" disabled={processing} className="btn" style={{ flex: 1, background: 'rgba(40,167,69,0.8)', border: 'none', padding: '10px', color: '#fff' }}>Simpan Perubahan</button>
                            </div>
                        </form>

                        <form onSubmit={handleDelete} style={{ marginTop: '10px' }}>
                            <button type="submit" disabled={processing} className="btn" style={{ width: '100%', background: 'rgba(220,53,69,0.5)', border: '1px solid rgba(220,53,69,0.8)', padding: '10px', color: '#fff' }}>Hapus Permanen</button>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
