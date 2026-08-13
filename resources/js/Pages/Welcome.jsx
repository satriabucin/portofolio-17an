import { useEffect, useState } from 'react';
import { Link, Head } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import { motion } from 'framer-motion';
import BlurText from '../Components/ReactBits/BlurText';
import TrueFocus from '../Components/ReactBits/TrueFocus';

export default function Welcome() {
    const [ssIndex, setSsIndex] = useState(0);

    const slides = [
        '/images/IMG_2443.JPG',
        '/images/IMG_2550.JPG',
        '/images/IMG_2582.JPG',
        '/images/IMG_2817.JPG',
        '/images/IMG_2892.JPG',
    ];

    useEffect(() => {
        const timer = setInterval(() => {
            setSsIndex((prev) => (prev + 1) % slides.length);
        }, 4000);
        return () => clearInterval(timer);
    }, [slides.length]);

    const moveSsSlide = (dir) => {
        setSsIndex((prev) => (prev + dir + slides.length) % slides.length);
    };

    // Load particles.js
    useEffect(() => {
        const script = document.createElement('script');
        script.src = "https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js";
        script.async = true;
        document.body.appendChild(script);
        
        script.onload = () => {
            if (window.particlesJS) {
                // Initialize particles with default config if needed or keep it simple
            }
        };
        return () => { document.body.removeChild(script); };
    }, []);

    return (
        <PublicLayout title="Beranda - 17 Agustus">
            <style>{`
                body { background-color: #0a0a0a; color: #ffffff; overflow-x: hidden; }
                .hero { background: linear-gradient(135deg, rgba(10, 10, 10, 0.9) 0%, rgba(30, 5, 5, 0.8) 100%), url('/images/IMG_2704.JPG') no-repeat center center; background-size: cover; background-attachment: fixed; padding: 140px 0 100px 0; min-height: 100vh; display: flex; alignItems: center; position: relative; overflow: hidden; }
                #particles-js { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }
                .hero-content { position: relative; z-index: 2; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 50px; width: 100%; }
                .hero-text { flex: 1 1 400px; max-width: 650px; }
                .hero-text .hero-title { font-size: clamp(2.5rem, 8vw, 5rem); line-height: 1.1; margin-bottom: 20px; font-weight: 800; color: #fff; letter-spacing: -1.5px; text-shadow: 0 10px 30px rgba(0,0,0,0.5); }
                .hero-text p { font-size: clamp(1rem, 2vw, 1.25rem); color: rgba(255,255,255,0.8); margin-bottom: 40px; max-width: 500px; line-height: 1.6; }
                .hero-visual { flex: 1 1 350px; position: relative; }
                .ss-card { background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 12px; box-shadow: 0 30px 60px rgba(0,0,0,0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); position: relative; z-index: 3; }
                .focus-container.hero-h2 { font-size: 2.5rem; margin-bottom: 40px; color: #111; }
                .ss-carousel { position: relative; aspect-ratio: 4/5; overflow: hidden; border-radius: 2px; background: #eee; }
                .ss-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1); transform: scale(1.05); }
                .ss-slide.active { opacity: 1; transform: scale(1); }
                .ss-slide img { width: 100%; height: 100%; object-fit: cover; }
                .ss-nav { display: flex; justifyContent: space-between; alignItems: center; margin-top: 15px; padding: 0 5px; color: #111; }
                @media(max-width: 768px) {
                    .hero { padding: 100px 0 40px 0; align-items: flex-start; }
                    .hero-content { flex-direction: column; gap: 20px; text-align: center; justify-content: center; }
                    .hero-text { width: 100%; flex-basis: auto; max-width: 100%; }
                    .hero-text p { margin-left: auto; margin-right: auto; }
                    .hero-text .hero-title { font-size: clamp(1.8rem, 9vw, 3.5rem); margin-bottom: 0px; line-height: 1.1; }
                    .focus-container.hero-h2 { font-size: 1.8rem; margin-bottom: 20px; }
                    .hero-visual { width: 100%; flex-basis: auto; }
                    .ss-card { padding: 10px; margin-bottom: 15px !important; }
                    .ss-carousel { aspect-ratio: 4/3; }
                    .ss-nav { flex-direction: row; gap: 10px; }
                }
            `}</style>
            
            <div className="hero">
                <div id="particles-js"></div>
                <div className="container hero-content">
                    <div className="hero-text">
                        <motion.p initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }} style={{ color: '#ff4747', fontWeight: 'bold', letterSpacing: '2px', textTransform: 'uppercase', marginBottom: '15px', fontSize: '0.9rem' }}>Peringatan HUT RI</motion.p>
                        <BlurText as="h1" text="Rayakan Keseruan 17 Agustus Bersama Kami." className="hero-title" delay={80} animateBy="words" direction="bottom" />
                    </div>
                    <motion.div initial={{ opacity: 0, scale: 0.95 }} whileInView={{ opacity: 1, scale: 1 }} viewport={{ once: true }} transition={{ duration: 0.8, delay: 0.3 }} className="hero-visual" id="galeri">
                        <div className="ss-card" style={{ marginBottom: '25px' }}>
                            <div className="ss-carousel">
                                {slides.map((src, index) => (
                                    <div key={index} className={`ss-slide ${index === ssIndex ? 'active' : ''}`}>
                                        <img src={src} alt={`Acara ${index + 1}`} />
                                    </div>
                                ))}
                            </div>
                            <div className="ss-nav">
                                <div style={{ fontSize: '0.9rem', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '1px', color: '#fff' }}>Galeri Acara</div>
                                <div style={{ display: 'flex', gap: '8px' }}>
                                    <button onClick={() => moveSsSlide(-1)} style={{ background: 'rgba(255,255,255,0.2)', color: '#fff', border: '1px solid rgba(255,255,255,0.3)', width: '35px', height: '35px', borderRadius: '50%', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>❮</button>
                                    <button onClick={() => moveSsSlide(1)} style={{ background: 'rgba(255,255,255,0.2)', color: '#fff', border: '1px solid rgba(255,255,255,0.3)', width: '35px', height: '35px', borderRadius: '50%', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>❯</button>
                                </div>
                            </div>
                        </div>
                        <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.5, delay: 0.5 }} style={{ display: 'flex', justifyContent: 'center', width: '100%' }}>
                            <Link href="/daftar" className="btn btn-glow" style={{ background: 'var(--color-primary)', color: '#fff', padding: '15px 30px', fontWeight: 'bold', fontSize: '1.1rem', borderRadius: '4px', width: '100%', textAlign: 'center', textDecoration: 'none' }}>DAFTAR SEKARANG</Link>
                        </motion.div>
                    </motion.div>
                </div>
            </div>

            <section style={{ background: '#fff', color: '#111', padding: '80px 0' }}>
                <div className="container">
                    <TrueFocus sentence="Kenapa Harus Join?" manualMode={false} blurAmount={4} borderColor="var(--color-primary)" glowColor="rgba(255, 71, 71, 0.4)" animationDuration={0.8} pauseBetweenAnimations={0.5} className="hero-h2" />
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '30px', marginTop: '40px' }}>
                        <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }} style={{ padding: '30px', background: '#fafafa', borderRadius: '8px' }}>
                            <h3 style={{ fontSize: '1.25rem', marginBottom: '15px' }}>Daftar Hitungan Detik</h3>
                            <p style={{ color: '#666', lineHeight: 1.6 }}>Tinggalkan cara lama. Buka web via HP, isi data, beres.</p>
                        </motion.div>
                        <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.6, delay: 0.1 }} style={{ padding: '30px', background: '#fafafa', borderRadius: '8px' }}>
                            <h3 style={{ fontSize: '1.25rem', marginBottom: '15px' }}>Pantau Real Time</h3>
                            <p style={{ color: '#666', lineHeight: 1.6 }}>Cek jadwal lomba lengkap dan susunan acara secara langsung dari HP kamu.</p>
                        </motion.div>
                        <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.6, delay: 0.2 }} style={{ padding: '30px', background: '#fafafa', borderRadius: '8px' }}>
                            <h3 style={{ fontSize: '1.25rem', marginBottom: '15px' }}>Simpan Kenangan</h3>
                            <p style={{ color: '#666', lineHeight: 1.6 }}>Semua momen keseruan masuk galeri publik. Kamu bisa buka dan kenang kapan pun.</p>
                        </motion.div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
