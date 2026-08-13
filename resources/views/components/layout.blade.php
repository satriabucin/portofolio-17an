<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Peringatan 17 Agustus' }}</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ff4747">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <script>
        // Check for saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        /* CSS Untuk Carousel Interaktif */
        .carousel-section {
            padding: 80px 0;
            background: #fff;
            overflow: hidden;
        }
        .carousel-container {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            aspect-ratio: 16/9;
        }
        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
            transform: scale(1.05);
            z-index: 1;
        }
        .carousel-slide.active {
            opacity: 1;
            transform: scale(1);
            z-index: 2;
        }
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }
        .carousel-caption {
            position: absolute;
            bottom: 30px;
            left: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            color: #fff;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.5s ease 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .carousel-slide.active .carousel-caption {
            transform: translateY(0);
            opacity: 1;
        }
        .carousel-caption h3 {
            margin: 0 0 5px 0;
            font-size: 1.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .carousel-caption p {
            margin: 0;
            font-size: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.3);
            backdrop-filter: blur(5px);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .carousel-btn:hover {
            background: rgba(255,255,255,0.8);
            color: var(--color-primary-dark);
            transform: translateY(-50%) scale(1.1);
        }
        .carousel-btn.prev { left: 20px; }
        .carousel-btn.next { right: 20px; }
        .carousel-dots {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dot.active {
            background: var(--color-primary);
            width: 30px;
            border-radius: 10px;
        }
        /* Custom Cursor */
        body {
            cursor: none;
        }
        
        input, textarea, select {
            cursor: text !important;
        }
        
        .cursor-dot, .cursor-outline {
            position: fixed;
            top: 0; left: 0;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: 99999;
            pointer-events: none;
        }
        
        .cursor-dot {
            width: 8px;
            height: 8px;
            background-color: var(--color-primary);
        }
        
        .cursor-outline {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255, 71, 71, 0.5);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
        }
        
        .cursor-outline.hover {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 71, 71, 0.1);
            border-color: transparent;
        }
        /* Skeleton Loading */
        .skeleton {
            position: relative;
            overflow: hidden;
            background-color: var(--glass-bg) !important;
            border-color: transparent !important;
            color: transparent !important;
        }
        .skeleton * {
            visibility: hidden;
        }
        .skeleton::after {
            content: "";
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            transform: translateX(-100%);
            background-image: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0,
                rgba(255, 255, 255, 0.05) 20%,
                rgba(255, 255, 255, 0.1) 60%,
                rgba(255, 255, 255, 0)
            );
            animation: shimmer 2s infinite;
        }
        [data-theme="light"] .skeleton::after {
            background-image: linear-gradient(
                90deg,
                rgba(0, 0, 0, 0) 0,
                rgba(0, 0, 0, 0.05) 20%,
                rgba(0, 0, 0, 0.1) 60%,
                rgba(0, 0, 0, 0)
            );
        }
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        /* Theme Toggle Button */
        .theme-toggle {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--color-text);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .theme-toggle:hover {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
        }
        [data-theme="light"] .theme-toggle {
            background: rgba(0,0,0,0.05);
            border-color: rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="skeleton-onload">
    <div class="cursor-dot" data-cursor-dot></div>
    <div class="cursor-outline" data-cursor-outline></div>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="navbar-brand">17 Agustus</a>
            <div style="display: flex; align-items: center; gap: 20px;">
                <button class="theme-toggle" id="themeToggleBtn" aria-label="Toggle Theme">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" id="themeIcon">
                        <!-- Default to Sun icon initially -->
                        <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .708-.708l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                    </svg>
                </button>
                <a href="{{ url('/galeri') }}" class="nav-link" style="color: var(--color-text); font-weight: 500;">Galeri</a>
                <a href="{{ url('/daftar') }}" class="btn" style="background: var(--color-primary); color: #fff; padding: 8px 16px; font-size: 0.9rem; border-radius: 6px;">Daftar Lomba</a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer style="background-color: var(--color-primary-dark); color: var(--color-background); padding: 0 0 40px 0; text-align: center; margin-top: 40px;">
        <div class="walking-parade-container" style="margin-bottom: 40px;">
            <div class="walking-parade">
                <span>🏃‍♂️</span> <span>🏃‍♀️</span> <span>⚽</span> <span>🧑‍🤝‍🧑</span> <span>🚩</span> <span>🚶‍♂️</span> <span>🏃‍♀️</span> <span>🎉</span> <span>🧑‍🌾</span> <span>🎈</span> <span>🏃‍♂️</span> <span>🤸‍♀️</span> <span>🏆</span> <span>🧑‍🤝‍🧑</span> <span>🏃‍♀️</span> <span>⚽</span>
            </div>
            <div class="walking-parade">
                <span>🏃‍♂️</span> <span>🏃‍♀️</span> <span>⚽</span> <span>🧑‍🤝‍🧑</span> <span>🚩</span> <span>🚶‍♂️</span> <span>🏃‍♀️</span> <span>🎉</span> <span>🧑‍🌾</span> <span>🎈</span> <span>🏃‍♂️</span> <span>🤸‍♀️</span> <span>🏆</span> <span>🧑‍🤝‍🧑</span> <span>🏃‍♀️</span> <span>⚽</span>
            </div>
        </div>
        <div class="container">
            <p style="margin: 0;">&copy; {{ date('Y') }} Peringatan 17 Agustus. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>
        // Custom Cursor
        const cursorDot = document.querySelector('[data-cursor-dot]');
        const cursorOutline = document.querySelector('[data-cursor-outline]');
        
        window.addEventListener('mousemove', function (e) {
            const posX = e.clientX;
            const posY = e.clientY;
            
            cursorDot.style.left = `${posX}px`;
            cursorDot.style.top = `${posY}px`;
            
            cursorOutline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 500, fill: "forwards" });
        });
        
        // Add hover effect to links and buttons
        const hoverElements = document.querySelectorAll('a, button, .gallery-item');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorOutline.classList.add('hover');
            });
            el.addEventListener('mouseleave', () => {
                cursorOutline.classList.remove('hover');
            });
        });

        // Initialize Vanilla Tilt for Glass Cards
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
        // Skeleton Loading Logic
        document.addEventListener("DOMContentLoaded", () => {
            // Apply skeleton to major cards initially
            const skeletonElements = document.querySelectorAll('.glass-card, .gallery-item, .hero-text');
            skeletonElements.forEach(el => el.classList.add('skeleton'));
            
            // Remove skeleton after 800ms (simulate load)
            setTimeout(() => {
                skeletonElements.forEach(el => el.classList.remove('skeleton'));
            }, 800);
        });

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeIcon = document.getElementById('themeIcon');
        
        function updateThemeIcon(theme) {
            if(theme === 'light') {
                themeIcon.innerHTML = '<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>';
            } else {
                themeIcon.innerHTML = '<path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .708-.708l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>';
            }
        }
        
        // Initial icon
        updateThemeIcon(document.documentElement.getAttribute('data-theme'));

        themeToggleBtn.addEventListener('click', () => {
            let currentTheme = document.documentElement.getAttribute('data-theme');
            let newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</body>
</html>
