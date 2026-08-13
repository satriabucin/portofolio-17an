import { useEffect, useRef, useState } from 'react';
import { Link, Head, usePage } from '@inertiajs/react';
import VanillaTilt from 'vanilla-tilt';
import { motion, AnimatePresence } from 'framer-motion';
import toast from 'react-hot-toast';

export default function PublicLayout({ children, title = 'Peringatan 17 Agustus' }) {
    const { url, props } = usePage();
    const { flash, errors } = props;
    const [theme, setTheme] = useState('dark');
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const cursorDotRef = useRef(null);
    const cursorOutlineRef = useRef(null);
    
    // Intercept flash messages for Toast
    useEffect(() => {
        if (flash?.success) {
            toast.success(flash.success);
        }
        if (flash?.error) {
            toast.error(flash.error);
        }
    }, [flash]);

    // Intercept validation errors for Toast
    useEffect(() => {
        if (errors && Object.keys(errors).length > 0) {
            const firstError = Object.values(errors)[0];
            toast.error(`Perhatian: ${firstError}`, { duration: 5000 });
        }
    }, [errors]);

    useEffect(() => {
        // Theme initialization
        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);
        document.documentElement.setAttribute('data-theme', savedTheme);

        // Custom Cursor Logic
        const handleMouseMove = (e) => {
            const posX = e.clientX;
            const posY = e.clientY;

            if (cursorDotRef.current && cursorOutlineRef.current) {
                cursorDotRef.current.style.left = `${posX}px`;
                cursorDotRef.current.style.top = `${posY}px`;

                cursorOutlineRef.current.animate({
                    left: `${posX}px`,
                    top: `${posY}px`
                }, { duration: 500, fill: "forwards" });
            }
        };
        window.addEventListener('mousemove', handleMouseMove);

        // Add hover effect
        const addHover = () => cursorOutlineRef.current?.classList.add('hover');
        const removeHover = () => cursorOutlineRef.current?.classList.remove('hover');
        
        const hoverElements = document.querySelectorAll('a, button, .gallery-item');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', addHover);
            el.addEventListener('mouseleave', removeHover);
        });

        // Skeleton Loading Simulate
        const skeletonElements = document.querySelectorAll('.glass-card, .gallery-item, .hero-text');
        skeletonElements.forEach(el => el.classList.add('skeleton'));
        
        const timer = setTimeout(() => {
            skeletonElements.forEach(el => el.classList.remove('skeleton'));
        }, 800);

        return () => {
            window.removeEventListener('mousemove', handleMouseMove);
            hoverElements.forEach(el => {
                el.removeEventListener('mouseenter', addHover);
                el.removeEventListener('mouseleave', removeHover);
            });
            clearTimeout(timer);
        };
    }, []);

    // Initialize Vanilla Tilt when children changes
    useEffect(() => {
        if (window.innerWidth > 768) {
            VanillaTilt.init(document.querySelectorAll(".glass-card"), {
                max: 5,
                speed: 400,
                glare: true,
                "max-glare": 0.2,
                scale: 1.02,
                gyroscope: false
            });
        }
    }, [children]);

    const toggleTheme = () => {
        const newTheme = theme === 'light' ? 'dark' : 'light';
        setTheme(newTheme);
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    };

    return (
        <div className="skeleton-onload">
            <Head title={title} />
            <style>{`
                .walking-parade-container { overflow: hidden; white-space: nowrap; display: flex; flex-wrap: nowrap; width: 100%; position: relative; }
                .walking-parade { display: flex; white-space: nowrap; animation: paradeScroll 30s linear infinite; }
                .walking-parade span { font-size: 1.5rem; margin: 0 15px; }
                @keyframes paradeScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
                
                .theme-toggle-btn {
                    background: var(--glass-bg);
                    border: 1px solid var(--glass-border);
                    color: var(--color-text);
                    width: 38px;
                    height: 38px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                .theme-toggle-btn:hover {
                    background: var(--color-primary);
                    color: #fff;
                    transform: rotate(15deg);
                }
            `}</style>
            <div className="cursor-dot" ref={cursorDotRef}></div>
            <div className="cursor-outline" ref={cursorOutlineRef}></div>
            
            <nav className="navbar">
                <div className="nav-container">
                    <Link href="/" className="navbar-brand">17 Agustus</Link>
                    <button className="mobile-menu-toggle" onClick={() => setMobileMenuOpen(!mobileMenuOpen)}>
                        ☰
                    </button>
                    <div className={`nav-links ${mobileMenuOpen ? 'open' : ''}`}>
                        <Link href="/jadwal" className="nav-link" style={{ color: 'var(--color-text)', fontWeight: 500 }} onClick={() => setMobileMenuOpen(false)}>Jadwal</Link>
                        <button className="theme-toggle-btn" onClick={toggleTheme} aria-label="Toggle Theme">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                {theme === 'light' ? (
                                    <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                                ) : (
                                    <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .708-.708l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                )}
                            </svg>
                        </button>
                        <Link href="/galeri" className="nav-link" style={{ color: 'var(--color-text)', fontWeight: 500 }} onClick={() => setMobileMenuOpen(false)}>Galeri</Link>
                        <Link href="/daftar" className="btn" style={{ background: 'var(--color-primary)', color: '#fff', padding: '8px 16px', fontSize: '0.9rem', borderRadius: '6px' }} onClick={() => setMobileMenuOpen(false)}>Daftar Lomba</Link>
                    </div>
                </div>
            </nav>

            <main>
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
            </main>

            <footer style={{ backgroundColor: 'var(--color-primary-dark)', color: 'var(--color-background)', padding: '0 0 40px 0', textAlign: 'center', marginTop: '40px', overflow: 'hidden' }}>
                <div className="walking-parade-container" style={{ marginBottom: '40px', display: 'flex', flexWrap: 'nowrap', overflow: 'hidden', whiteSpace: 'nowrap' }}>
                    <div className="walking-parade">
                        <span>🏃‍♂️</span> <span>🏃‍♀️</span> <span>⚽</span> <span>🧑‍🤝‍🧑</span> <span>🚩</span> <span>🚶‍♂️</span> <span>🏃‍♀️</span> <span>🎉</span> <span>🧑‍🌾</span> <span>🎈</span> <span>🏃‍♂️</span> <span>🤸‍♀️</span> <span>🏆</span> <span>🧑‍🤝‍🧑</span> <span>🏃‍♀️</span> <span>⚽</span>
                    </div>
                    <div className="walking-parade">
                        <span>🏃‍♂️</span> <span>🏃‍♀️</span> <span>⚽</span> <span>🧑‍🤝‍🧑</span> <span>🚩</span> <span>🚶‍♂️</span> <span>🏃‍♀️</span> <span>🎉</span> <span>🧑‍🌾</span> <span>🎈</span> <span>🏃‍♂️</span> <span>🤸‍♀️</span> <span>🏆</span> <span>🧑‍🤝‍🧑</span> <span>🏃‍♀️</span> <span>⚽</span>
                    </div>
                </div>
                <div className="container">
                    <p style={{ margin: 0 }}>&copy; {new Date().getFullYear()} Peringatan 17 Agustus. All rights reserved.</p>
                </div>
            </footer>
        </div>
    );
}
