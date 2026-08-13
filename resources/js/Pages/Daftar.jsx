import { useState, useEffect } from 'react';
import { useForm, usePage, Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import toast from 'react-hot-toast';

export default function Daftar({ lombas }) {
    const { flash, errors } = usePage().props;
    const [step, setStep] = useState(1);
    const { data, setData, post, processing } = useForm({
        nama: '',
        no_hp: '',
        blok_rumah: '',
        rt: '',
        lombas: []
    });

    const handleCheckboxChange = (id) => {
        if (data.lombas.includes(id)) {
            setData('lombas', data.lombas.filter(l => l !== id));
        } else {
            setData('lombas', [...data.lombas, id]);
        }
    };

    const nextStep = (currentStep) => {
        if (currentStep === 1) {
            if (!data.nama || !data.no_hp) {
                toast.error("Perhatian: Mohon lengkapi Nama dan Nomor HP terlebih dahulu.");
                return;
            }
        } else if (currentStep === 2) {
            if (!data.blok_rumah || !data.rt) {
                toast.error("Perhatian: Mohon lengkapi Blok Rumah dan RT terlebih dahulu.");
                return;
            }
        }
        setStep(currentStep + 1);
    };

    const prevStep = (currentStep) => {
        setStep(currentStep - 1);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/daftar', {
            preserveScroll: true,
            onSuccess: () => {
                setStep(1);
                setData({
                    nama: '',
                    no_hp: '',
                    blok_rumah: '',
                    rt: '',
                    lombas: []
                });
            }
        });
    };

    useEffect(() => {
        if (flash?.success) {
            const script = document.createElement('script');
            script.src = "https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js";
            script.onload = () => {
                const duration = 3 * 1000;
                const animationEnd = Date.now() + duration;
                const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };

                function randomInRange(min, max) { return Math.random() * (max - min) + min; }

                const interval = setInterval(function() {
                    const timeLeft = animationEnd - Date.now();
                    if (timeLeft <= 0) return clearInterval(interval);
                    const particleCount = 50 * (timeLeft / duration);
                    confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }, colors: ['#ff0000', '#ffffff'] }));
                    confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }, colors: ['#ff0000', '#ffffff'] }));
                }, 250);
            };
            document.body.appendChild(script);
            return () => document.body.removeChild(script);
        }
    }, [flash]);

    return (
        <PublicLayout title="Daftar Lomba">
            <style>{`
                .wizard-progress { display: flex; justify-content: space-between; position: relative; margin-bottom: 40px; align-items: center; }
                .wizard-progress::before { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 2px; background: var(--glass-border); z-index: 1; transform: translateY(-50%); }
                .wizard-progress-bar { position: absolute; top: 50%; left: 0; height: 2px; background: var(--color-primary); z-index: 2; transform: translateY(-50%); transition: width 0.4s ease; }
                .wizard-step-dot { position: relative; width: 34px; height: 34px; border-radius: 50%; background: var(--color-background); border: 2px solid var(--glass-border); z-index: 3; display: flex; align-items: center; justify-content: center; color: var(--color-text); font-weight: bold; transition: all 0.4s ease; font-size: 0.9rem; line-height: 1; padding-bottom: 2px; box-sizing: border-box; }
                .wizard-step-dot.active { background: var(--color-primary); border-color: var(--color-primary); box-shadow: 0 0 15px rgba(255, 71, 71, 0.3); color: #fff; }
                .wizard-step-dot.completed { background: var(--color-primary); border-color: var(--color-primary); }
                .form-section { display: none; animation: fadeIn 0.5s; }
                .form-section.active { display: block; }
                @keyframes fadeIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
                
                .lomba-label { display: block; background: var(--glass-bg); padding: 20px; border-radius: 12px; margin-bottom: 15px; cursor: pointer; border: 1px solid var(--glass-border); transition: all 0.3s ease; position: relative; overflow: hidden; }
                .lomba-label:hover { border-color: rgba(255, 71, 71, 0.5); background: rgba(255, 71, 71, 0.05); transform: translateY(-2px); }
                .lomba-label input[type="checkbox"] { display: none; }
                .lomba-label input[type="checkbox"]:checked + .lomba-content { border-color: var(--color-primary); }
                .lomba-label input[type="checkbox"]:checked ~ .check-indicator { background: var(--color-primary); border-color: var(--color-primary); box-shadow: 0 0 10px rgba(255, 71, 71, 0.5); }
                .lomba-label input[type="checkbox"]:checked ~ .check-indicator::after { display: block; }
                .check-indicator { position: absolute; top: 20px; right: 20px; width: 24px; height: 24px; border-radius: 6px; border: 2px solid var(--glass-border); transition: all 0.3s ease; background: rgba(0,0,0,0.2); }
                .check-indicator::after { content: ''; position: absolute; display: none; left: 7px; top: 2px; width: 6px; height: 12px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
                
                .form-control { background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text); padding: 15px; border-radius: 8px; width: 100%; box-sizing: border-box; }
                .form-label { display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
            `}</style>

            <div className="container" style={{ paddingTop: '40px', paddingBottom: '80px', position: 'relative' }}>
                <Link href="/" className="btn btn-glow" style={{ position: 'absolute', top: '10px', left: '20px', padding: '8px 16px', background: 'rgba(255, 255, 255, 0.1)', color: 'var(--color-text)', borderRadius: '8px', textDecoration: 'none', border: '1px solid var(--glass-border)', display: 'inline-flex', alignItems: 'center', gap: '8px', fontSize: '0.9rem', zIndex: 10 }}>
                    &larr; Kembali ke Beranda
                </Link>
                <div className="glass-card" style={{ maxWidth: '650px', margin: '0 auto', padding: '50px 40px', marginTop: '40px' }}>
                    <h1 style={{ textAlign: 'center', marginBottom: '10px', color: 'var(--color-text)', fontWeight: 800 }}>Formulir Pendaftaran</h1>
                    <p style={{ textAlign: 'center', color: 'var(--color-text)', opacity: 0.6, marginBottom: '40px' }}>Ikuti 3 langkah mudah untuk bergabung dalam keseruan 17-an.</p>

                    <div className="wizard-progress">
                        <div className="wizard-progress-bar" style={{ width: step === 1 ? '0%' : step === 2 ? '50%' : '100%' }}></div>
                        <div className={`wizard-step-dot ${step === 1 ? 'active' : step > 1 ? 'completed' : ''}`}>1</div>
                        <div className={`wizard-step-dot ${step === 2 ? 'active' : step > 2 ? 'completed' : ''}`}>2</div>
                        <div className={`wizard-step-dot ${step === 3 ? 'active' : ''}`}>3</div>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className={`form-section ${step === 1 ? 'active' : ''}`}>
                            <h3 style={{ marginBottom: '25px', color: 'var(--color-primary)', fontSize: '1.3rem' }}>Langkah 1: Data Diri</h3>
                            <div style={{ marginBottom: '25px' }}>
                                <label className="form-label">Nama Lengkap</label>
                                <input type="text" className="form-control" value={data.nama} onChange={e => setData('nama', e.target.value)} required={step === 1} placeholder="Contoh: Budi Santoso" />
                            </div>
                            <div style={{ marginBottom: '30px' }}>
                                <label className="form-label">Nomor HP / WhatsApp</label>
                                <input type="tel" className="form-control" value={data.no_hp} onChange={e => setData('no_hp', e.target.value)} required={step === 1} placeholder="Contoh: 08123456789" />
                            </div>
                            <div style={{ textAlign: 'right' }}>
                                <button type="button" className="btn btn-primary" onClick={() => nextStep(1)} style={{ padding: '12px 30px', borderRadius: '8px' }}>Selanjutnya &rarr;</button>
                            </div>
                        </div>

                        <div className={`form-section ${step === 2 ? 'active' : ''}`}>
                            <h3 style={{ marginBottom: '25px', color: 'var(--color-primary)', fontSize: '1.3rem' }}>Langkah 2: Alamat Tinggal</h3>
                            <div style={{ marginBottom: '25px' }}>
                                <label className="form-label">Blok / Nomor Rumah</label>
                                <input type="text" className="form-control" value={data.blok_rumah} onChange={e => setData('blok_rumah', e.target.value)} required={step === 2} placeholder="Contoh: Blok A No. 12" />
                            </div>
                            <div style={{ marginBottom: '30px' }}>
                                <label className="form-label">RT (Rukun Tetangga)</label>
                                <select className="form-control" style={{ background: 'var(--color-background)' }} value={data.rt} onChange={e => setData('rt', e.target.value)} required={step === 2}>
                                    <option value="">-- Pilih RT --</option>
                                    <option value="RT 01">RT 01</option>
                                    <option value="RT 02">RT 02</option>
                                    <option value="RT 03">RT 03</option>
                                    <option value="RT 04">RT 04</option>
                                </select>
                            </div>
                            <div style={{ display: 'flex', gap: '15px' }}>
                                <button type="button" className="btn" onClick={() => prevStep(2)} style={{ background: 'var(--glass-bg)', color: 'var(--color-text)', padding: '12px 20px', borderRadius: '8px', flex: 1 }}>&larr; Kembali</button>
                                <button type="button" className="btn btn-primary" onClick={() => nextStep(2)} style={{ padding: '12px 20px', borderRadius: '8px', flex: 1 }}>Selanjutnya &rarr;</button>
                            </div>
                        </div>

                        <div className={`form-section ${step === 3 ? 'active' : ''}`}>
                            <h3 style={{ marginBottom: '10px', color: 'var(--color-primary)', fontSize: '1.3rem' }}>Langkah 3: Pilihan Lomba</h3>
                            <p style={{ color: 'var(--color-text)', opacity: 0.7, fontSize: '0.9rem', marginBottom: '25px' }}>Anda dapat mengikuti lebih dari satu lomba.</p>
                            
                            <div style={{ maxHeight: '400px', overflowY: 'auto', paddingRight: '10px', marginBottom: '30px' }}>
                                {lombas.length > 0 ? lombas.map((lomba) => (
                                    <label key={lomba.id} className="lomba-label">
                                        <input type="checkbox" checked={data.lombas.includes(lomba.id)} onChange={() => handleCheckboxChange(lomba.id)} />
                                        <div className="lomba-content">
                                            <strong style={{ color: 'var(--color-text)', fontSize: '1.1rem', display: 'block', marginBottom: '5px' }}>{lomba.nama_lomba}</strong>
                                            <span style={{ display: 'inline-block', background: 'rgba(255, 71, 71, 0.2)', color: '#ff4747', padding: '3px 8px', borderRadius: '4px', fontSize: '0.75rem', fontWeight: 'bold', marginBottom: '8px' }}>{lomba.kategori_usia}</span>
                                            {lomba.kuota && (
                                                <span style={{ display: 'inline-block', background: 'var(--glass-bg)', color: 'var(--color-text)', opacity: 0.8, padding: '3px 8px', borderRadius: '4px', fontSize: '0.75rem', fontWeight: 'bold', marginBottom: '8px', marginLeft: '5px' }}>
                                                    Sisa Kuota: {lomba.kuota - lomba.peserta_count}
                                                </span>
                                            )}
                                            <div style={{ marginBottom: '8px', display: 'flex', flexDirection: 'column', gap: '4px', fontSize: '0.85rem', color: 'var(--color-text)', opacity: 0.9 }}>
                                                {lomba.jadwal_waktu && <div><strong style={{ color: 'var(--color-primary)' }}>📅</strong> {new Date(lomba.jadwal_waktu).toLocaleString('id-ID')}</div>}
                                                {lomba.lokasi && <div><strong style={{ color: 'var(--color-primary)' }}>📍</strong> {lomba.lokasi}</div>}
                                            </div>
                                            <p style={{ color: 'var(--color-text)', opacity: 0.6, fontSize: '0.9rem', margin: 0 }}>{lomba.deskripsi || 'Tidak ada deskripsi'}</p>
                                        </div>
                                        <div className="check-indicator"></div>
                                    </label>
                                )) : (
                                    <div style={{ textAlign: 'center', padding: '30px', background: 'var(--glass-bg)', borderRadius: '8px' }}>
                                        <p style={{ color: 'var(--color-text)', opacity: 0.6, margin: 0 }}>Belum ada lomba yang dibuka.</p>
                                    </div>
                                )}
                            </div>

                            <div style={{ display: 'flex', gap: '15px' }}>
                                <button type="button" className="btn" onClick={() => prevStep(3)} style={{ background: 'var(--glass-bg)', color: 'var(--color-text)', padding: '12px 20px', borderRadius: '8px', flex: 1 }}>&larr; Kembali</button>
                                <button type="submit" disabled={processing} className="btn btn-primary btn-glow" style={{ padding: '12px 20px', borderRadius: '8px', fontWeight: 'bold', border: 'none', opacity: processing ? 0.7 : 1, flex: 1 }}>
                                    {processing ? 'Memproses...' : 'Daftar'}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </PublicLayout>
    );
}
