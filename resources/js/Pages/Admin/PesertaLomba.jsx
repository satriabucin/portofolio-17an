import { useForm, usePage } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function PesertaLomba({ lombas }) {
    const { flash } = usePage().props;

    const printLomba = (lombaId, lombaNama, kategori, anggotaPerTim) => {
        const targetCard = document.getElementById('lomba-card-' + lombaId);
        if (!targetCard) return;
        
        const scrollContainer = targetCard.querySelector('.scroll-container');
        let tablesHtml = scrollContainer ? scrollContainer.innerHTML : '<div style="text-align: center; color: #888;">Belum ada peserta</div>';

        const printContents = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak - ${lombaNama}</title>
                <style>
                    @page { size: A4; margin: 1.5cm; }
                    body { font-family: Arial, sans-serif; color: #000; background: #fff; margin: 0; padding: 0; }
                    h2 { margin-bottom: 5px; color: #000; font-size: 24px; text-align: center; }
                    .meta-container { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
                    .meta-item { font-size: 14px; color: #333; margin-top: 5px; }
                    h4 { margin-top: 25px; margin-bottom: 10px; color: #000; font-size: 16px; font-weight: bold; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
                    th, td { border: 1px solid #000 !important; padding: 10px; text-align: left; color: #000 !important; }
                    th { background-color: #e0e0e0 !important; font-weight: bold; }
                    * { max-height: none !important; overflow: visible !important; background-color: transparent !important; }
                </style>
            </head>
            <body>
                <div class="meta-container">
                    <h2>${lombaNama}</h2>
                    <div class="meta-item">Kategori: ${kategori || 'Umum'}</div>
                    ${anggotaPerTim > 1 ? `<div class="meta-item" style="font-weight: bold;">Lomba Beregu (${anggotaPerTim} Orang/Tim)</div>` : ''}
                </div>
                ${tablesHtml}
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContents);
        printWindow.document.close();
        
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 250);
    };

    return (
        <AdminLayout title="Daftar Peserta Lomba">
            <style>{`
                @media print {
                    @page { size: A4; margin: 1.5cm; }
                    body { background: #fff !important; color: #000 !important; overflow: visible !important; }
                    .sidebar, .menu-toggle, button, form { display: none !important; }
                    .content { padding: 0 !important; margin: 0 !important; width: 100% !important; }
                    .admin-layout { display: block !important; width: 100% !important; }
                    .print-area { position: static !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
                    .glass-card { background: none !important; border: none !important; color: #000 !important; box-shadow: none !important; padding: 0 !important; margin: 0 0 30px 0 !important; page-break-inside: auto; }
                    th, td, h2, h3, h4, div { color: #000 !important; border-color: #ccc !important; }
                    .scroll-container, .table-responsive { max-height: none !important; overflow: visible !important; display: block !important; width: 100% !important; }
                    table { background: transparent !important; width: 100% !important; min-width: auto !important; }
                    thead tr { background: transparent !important; }
                    .content > div:first-child { display: none !important; }
                }
                @media screen {
                    .scroll-container { max-height: 500px; overflow-y: auto; padding-right: 10px; }
                }
            `}</style>

            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: '15px', marginBottom: '20px' }}>
                <div>
                    <h1 style={{ marginBottom: '5px' }}>Daftar Peserta Per Lomba</h1>
                    <p style={{ opacity: 0.8 }}>Rekapitulasi warga yang telah diverifikasi dan siap mengikuti lomba.</p>
                </div>
                <button onClick={() => window.print()} className="btn btn-accent" style={{ padding: '10px 15px', borderRadius: '6px', border: 'none', cursor: 'pointer' }}>🖨️ Cetak Rekap</button>
            </div>

            <div className="print-area">
                {lombas.map(lomba => (
                    <LombaCard key={lomba.id} lomba={lomba} printLomba={printLomba} />
                ))}
            </div>
        </AdminLayout>
    );
}

function LombaCard({ lomba, printLomba }) {
    const { data: randomData, setData: setRandomData, post: postRandom, processing: randomProcessing } = useForm({
        jumlah_sesi: '1'
    });
    const { post: postReset, processing: resetProcessing } = useForm();

    const handleRandom = (e) => {
        e.preventDefault();
        if (confirm('Acak sesi untuk lomba ini?')) {
            postRandom(`/admin/lomba/${lomba.id}/randomize`);
        }
    };

    const handleReset = (e) => {
        e.preventDefault();
        if (confirm('Reset sesi ke awal (Belum Dibagi)?')) {
            postReset(`/admin/lomba/${lomba.id}/reset-sesi`);
        }
    };

    // Grouping
    const groupedPendaftars = lomba.pendaftars.reduce((acc, p) => {
        const sesi = p.pivot.sesi || 'Belum Dibagi';
        if (!acc[sesi]) acc[sesi] = [];
        acc[sesi].push(p);
        return acc;
    }, {});

    return (
        <div id={`lomba-card-${lomba.id}`} className="glass-card lomba-card" style={{ padding: '25px', marginBottom: '25px' }}>
            <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ borderBottom: '2px solid var(--color-primary)', paddingBottom: '15px', marginBottom: '20px', justifyContent: 'space-between', alignItems: 'flex-start', gap: '15px' }}>
                <div>
                    <h2 style={{ margin: 0, color: 'var(--color-text)' }}>{lomba.nama_lomba}</h2>
                    <div style={{ color: 'var(--color-text)', opacity: 0.8, fontSize: '0.9rem', marginTop: '5px' }}>Kategori: {lomba.kategori_usia || 'Umum'}</div>
                    {lomba.jumlah_anggota_per_tim > 1 && (
                        <div style={{ color: '#ffc107', fontSize: '0.85rem', fontWeight: 'bold', marginTop: '3px' }}>Lomba Beregu ({lomba.jumlah_anggota_per_tim} Orang/Tim)</div>
                    )}
                </div>
                
                <div style={{ display: 'flex', gap: '10px', alignItems: 'center' }}>
                    <button onClick={() => printLomba(lomba.id, lomba.nama_lomba, lomba.kategori_usia, lomba.jumlah_anggota_per_tim)} className="btn" style={{ padding: '10px 15px', fontSize: '0.9rem', background: '#17a2b8', color: '#fff', border: 'none', fontWeight: 'bold', cursor: 'pointer', borderRadius: '6px' }}>🖨️ Cetak Lomba Ini</button>
                </div>
            </div>

            {lomba.pendaftars.length > 0 && (
                <div className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '10px', alignItems: 'flex-start' }}>
                    <form onSubmit={handleRandom} className="tw-flex tw-flex-col md:tw-flex-row" style={{ gap: '10px', alignItems: 'flex-start', background: 'var(--glass-bg)', padding: '10px', borderRadius: '6px', position: 'relative', zIndex: 50 }}>
                        <label style={{ color: 'var(--color-text)', fontSize: '0.9rem' }}>Bagi Sesi/Tim:</label>
                        <input type="number" min="1" max={lomba.pendaftars.length} required className="form-control" style={{ width: '80px', padding: '5px', background: 'var(--glass-bg)', border: '1px solid var(--glass-border)', color: 'var(--color-text)' }} placeholder="Jml" value={randomData.jumlah_sesi} onChange={e => setRandomData('jumlah_sesi', e.target.value)} />
                        <button type="submit" disabled={randomProcessing} className="btn" style={{ padding: '5px 10px', fontSize: '0.9rem', background: '#ffc107', color: '#000', border: 'none', fontWeight: 'bold', cursor: 'pointer', position: 'relative', zIndex: 55 }}>🎲 Acak</button>
                    </form>
                    
                    <form onSubmit={handleReset}>
                        <button type="submit" disabled={resetProcessing} className="btn" style={{ padding: '10px 15px', fontSize: '0.9rem', background: 'rgba(220,53,69,0.8)', color: '#fff', border: 'none', fontWeight: 'bold', cursor: 'pointer', borderRadius: '6px' }}>🔄 Reset</button>
                    </form>
                </div>
            )}

            {lomba.pendaftars.length > 0 ? (
                <div className="scroll-container">
                    {Object.keys(groupedPendaftars).map(sesi => (
                        <div key={sesi}>
                            {sesi !== 'Belum Dibagi' && (
                                <h4 style={{ marginTop: '20px', marginBottom: '10px', color: 'var(--color-primary)' }}>Kelompok / Sesi {sesi}</h4>
                            )}
                            <div className="table-responsive" style={{ marginBottom: '20px' }}>
                                <table style={{ width: '100%', borderCollapse: 'collapse', color: 'var(--color-text)', background: 'var(--glass-bg)' }}>
                                    <thead style={{ position: 'sticky', top: 0, background: 'var(--color-background)', zIndex: 10 }}>
                                        <tr style={{ background: 'var(--glass-bg)' }}>
                                            <th style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left', width: '50px' }}>No</th>
                                            {lomba.jumlah_anggota_per_tim > 1 && sesi !== 'Belum Dibagi' && (
                                                <th style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Tim</th>
                                            )}
                                            <th style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Nama Peserta</th>
                                            <th style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Blok & RT</th>
                                            <th style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', textAlign: 'left' }}>Catatan Panitia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {groupedPendaftars[sesi].map((peserta, index) => (
                                            <tr key={peserta.id}>
                                                <td style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)' }}>{index + 1}</td>
                                                {lomba.jumlah_anggota_per_tim > 1 && sesi !== 'Belum Dibagi' && (
                                                    <td style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', fontWeight: 'bold', color: '#ffc107' }}>
                                                        {peserta.pivot.tim ? 'Tim ' + peserta.pivot.tim : '-'}
                                                    </td>
                                                )}
                                                <td style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', fontWeight: 'bold' }}>{peserta.nama}</td>
                                                <td style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)', color: 'var(--color-text)', opacity: 0.8 }}>{peserta.blok_rumah} / {peserta.rt}</td>
                                                <td style={{ padding: '12px', borderBottom: '1px solid var(--glass-border)' }}>
                                                    <div style={{ borderBottom: '1px dashed var(--glass-border)', width: '100%', height: '20px' }}></div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div style={{ textAlign: 'center', padding: '20px', background: 'var(--glass-bg)', borderRadius: '6px', color: 'var(--color-text)', opacity: 0.7, marginTop: '15px' }}>
                    Belum ada peserta yang mendaftar dan diverifikasi untuk lomba ini.
                </div>
            )}
        </div>
    );
}
