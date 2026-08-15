import { useEffect, useRef, useState } from 'react';
import { Link, Head, usePage, router } from '@inertiajs/react';
import VanillaTilt from 'vanilla-tilt';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';

let globalAudioCtx = null;

export default function AdminLayout({ children, title = 'Panel Admin - Peringatan 17 Agustus' }) {
    const { url, props } = usePage();
    const { auth, flash, errors, unverified_count } = props;
    const [sidebarActive, setSidebarActive] = useState(false);
    const [theme, setTheme] = useState('dark');
    const cursorDotRef = useRef(null);
    const cursorOutlineRef = useRef(null);
    const previousCount = useRef(unverified_count);

    const lastFlash = useRef({ success: '', error: '' });

    // Initialize Global Audio Context on any user interaction
    useEffect(() => {
        const initAudio = () => {
            if (!globalAudioCtx) {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (AudioContext) {
                    globalAudioCtx = new AudioContext();
                }
            }
            if (globalAudioCtx && globalAudioCtx.state === 'suspended') {
                globalAudioCtx.resume();
            }
        };
        document.addEventListener('click', initAudio);
        return () => document.removeEventListener('click', initAudio);
    }, []);

    useEffect(() => {
        if (flash?.success && lastFlash.current.success !== flash.success) {
            toast.success(flash.success);
            lastFlash.current.success = flash.success;
        }
        if (flash?.error && lastFlash.current.error !== flash.error) {
            toast.error(flash.error);
            lastFlash.current.error = flash.error;
        }
    }, [flash]);

    // Polling for notification badge
    useEffect(() => {
        if (auth?.admin_id) {
            const interval = setInterval(() => {
                router.reload({ only: ['unverified_count'], preserveScroll: true, preserveState: true });
            }, 5000); // 5 detik
            return () => clearInterval(interval);
        }
    }, [auth]);

    // Play an iPhone-style "Ting" sound
    const playNotificationSound = () => {
        try {
            if (!globalAudioCtx) return;
            const ctx = globalAudioCtx;
            if (ctx.state === 'suspended') ctx.resume();
            
            // Oscillator 1: Main tone (C6)
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(1046.50, ctx.currentTime); 
            
            gain1.gain.setValueAtTime(0, ctx.currentTime);
            gain1.gain.linearRampToValueAtTime(0.8, ctx.currentTime + 0.01); // Sangat cepat (attack)
            gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6); // Memudar (decay)
            
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start();
            osc1.stop(ctx.currentTime + 0.6);
            
            // Oscillator 2: Harmoni nada (E6) untuk efek lonceng/kaca (bell-like)
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(1318.51, ctx.currentTime); 
            
            gain2.gain.setValueAtTime(0, ctx.currentTime);
            gain2.gain.linearRampToValueAtTime(0.3, ctx.currentTime + 0.01);
            gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
            
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start();
            osc2.stop(ctx.currentTime + 1);
        } catch (e) {
            console.log("Audio play failed or blocked by browser policy", e);
        }
    };

    // Theme initialization
    useEffect(() => {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);
        document.documentElement.setAttribute('data-theme', savedTheme);
    }, []);

    const toggleTheme = () => {
        const newTheme = theme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
        localStorage.setItem('theme', newTheme);
        document.documentElement.setAttribute('data-theme', newTheme);
    };

    // Toast when new pendaftar arrives
    useEffect(() => {
        if (unverified_count > previousCount.current && previousCount.current !== undefined) {
            playNotificationSound();
            toast.success(`Ada ${unverified_count - previousCount.current} pendaftar baru menunggu verifikasi!`, { duration: 4000, icon: '🔔', id: 'new-pendaftar' });
        }
        previousCount.current = unverified_count;
    }, [unverified_count]);

    // Intercept validation errors for Toast
    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            const firstError = Object.values(errors)[0];
            toast.error(`Perhatian: ${firstError}`, { duration: 5000 });
        }
    }, [errors]);

    useEffect(() => {
        const handleMouseMove = (e) => {
            const posX = e.clientX;
            const posY = e.clientY;
            if (cursorDotRef.current && cursorOutlineRef.current) {
                cursorDotRef.current.style.left = `${posX}px`;
                cursorDotRef.current.style.top = `${posY}px`;
                cursorOutlineRef.current.animate({ left: `${posX}px`, top: `${posY}px` }, { duration: 500, fill: "forwards" });
            }
        };
        window.addEventListener('mousemove', handleMouseMove);
        
        const addHover = () => cursorOutlineRef.current?.classList.add('hover');
        const removeHover = () => cursorOutlineRef.current?.classList.remove('hover');
        
        const hoverElements = document.querySelectorAll('a, button');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', addHover);
            el.addEventListener('mouseleave', removeHover);
        });

        return () => {
            window.removeEventListener('mousemove', handleMouseMove);
            hoverElements.forEach(el => {
                el.removeEventListener('mouseenter', addHover);
                el.removeEventListener('mouseleave', removeHover);
            });
        };
    }, []);

    useEffect(() => {
        if (window.innerWidth > 768) {
            VanillaTilt.init(document.querySelectorAll(".glass-card"), { max: 5, speed: 400, glare: true, "max-glare": 0.2, scale: 1.02, gyroscope: false });
        }
    }, [children]);

    return (
        <div style={{ background: theme === 'dark' ? 'linear-gradient(135deg, rgba(10, 10, 10, 0.9) 0%, rgba(30, 5, 5, 0.9) 100%)' : 'var(--color-background)', backgroundAttachment: 'fixed', color: 'var(--color-text)', minHeight: '100vh', display: 'flex', flexDirection: 'column', transition: 'background 0.3s ease' }}>
            <Head title={title} />
            <div className="cursor-dot" ref={cursorDotRef}></div>
            <div className="cursor-outline" ref={cursorOutlineRef}></div>
            
            <style>{`
                .admin-layout { display: flex; min-height: 100vh; flex-direction: column; }
                @media(min-width: 768px) { .admin-layout { flex-direction: row; } }
                .sidebar { width: 100%; background: var(--glass-bg); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); color: var(--color-text); padding: 20px; display: none; flex-direction: column; }
                .sidebar.active { display: flex; }
                @media(min-width: 768px) { .sidebar { display: flex; width: 250px; } }
                .menu-toggle { display: inline-block; background: var(--glass-bg); color: var(--color-text); border: 1px solid var(--glass-border); padding: 10px 15px; border-radius: 5px; cursor: pointer; font-size: 1.2rem; margin-bottom: 20px; }
                @media(min-width: 768px) { .menu-toggle { display: none; } }
                .sidebar a { color: var(--color-text); padding: 10px; margin-bottom: 5px; border-radius: 5px; display: block; text-decoration: none; }
                .sidebar a:hover { background: var(--glass-border); }
                .content { flex: 1; padding: 15px; background: transparent; width: 100%; box-sizing: border-box; }
                @media(min-width: 768px) { .content { padding: 30px; } }
                .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; display: block; }
                table { width: 100%; border-collapse: collapse; background: var(--glass-bg); min-width: 600px; color: var(--color-text); }
                th, td { padding: 12px; border: 1px solid var(--glass-border); text-align: left; }
                th { background: var(--glass-border); }
                
                /* Responsive Utility Classes */
                .tw-flex { display: flex; }
                .tw-flex-col { flex-direction: column; }
                @media (min-width: 768px) {
                    .md\\:tw-flex-row { flex-direction: row !important; }
                }
                
                .theme-toggle-btn {
                    background: var(--glass-bg);
                    border: 1px solid var(--glass-border);
                    color: var(--color-text);
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                .theme-toggle-btn:hover {
                    background: var(--color-primary);
                    color: white;
                    transform: rotate(15deg);
                }
            `}</style>

            <div className="admin-layout">
                {auth?.admin_id && (
                    <div className={`sidebar ${sidebarActive ? 'active' : ''}`}>
                        <h2 style={{ fontFamily: 'var(--font-heading)', marginBottom: '30px' }}>Admin Panel</h2>
                        <Link href="/admin/dashboard">Dashboard</Link>
                        <Link href="/admin/lomba">Master Lomba</Link>
                        <Link href="/admin/jadwal">Jadwal Acara</Link>
                        <Link href="/admin/pendaftar" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                            Verifikasi Pendaftar
                            {unverified_count > 0 && (
                                <span style={{ background: '#ff4747', color: '#fff', fontSize: '0.75rem', padding: '2px 8px', borderRadius: '12px', fontWeight: 'bold' }}>
                                    {unverified_count}
                                </span>
                            )}
                        </Link>
                        <Link href="/admin/peserta-lomba">Daftar Peserta Lomba</Link>
                        <Link href="/admin/audit-logs">Audit Trail</Link>
                        <div style={{ flex: 1 }}></div>
                        
                        <Link href="/admin/logout" style={{ background: 'rgba(255,0,0,0.2)', textAlign: 'center' }}>Logout</Link>
                    </div>
                )}
                
                <div className="content">
                    {auth?.admin_id && (
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
                            <button className="menu-toggle" style={{ margin: 0 }} onClick={() => setSidebarActive(!sidebarActive)}>☰ Menu Admin</button>
                            <button className="theme-toggle-btn" onClick={toggleTheme} aria-label="Toggle Theme" style={{ marginLeft: 'auto' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    {theme === 'light' ? (
                                        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                                    ) : (
                                        <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .708-.708l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                    )}
                                </svg>
                            </button>
                        </div>
                    )}
                    <AnimatePresence mode="wait">
                        <motion.div
                            key={url}
                            initial={{ opacity: 0, y: 15 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -15 }}
                            transition={{ duration: 0.3, ease: 'easeOut' }}
                        >
                            {children}
                        </motion.div>
                    </AnimatePresence>
                </div>
            </div>
        </div>
    );
}
