<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Peringatan 17 Agustus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        @media(min-width: 768px) {
            .admin-layout {
                flex-direction: row;
            }
        }
        .sidebar {
            width: 100%;
            background: rgba(255,255,255,0.02);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            color: #fff;
            padding: 20px;
            display: none; 
            flex-direction: column;
        }
        .sidebar.active {
            display: flex;
        }
        @media(min-width: 768px) {
            .sidebar {
                display: flex; /* Selalu tampil di desktop */
                width: 250px;
            }
        }
        .menu-toggle {
            display: inline-block;
            background: rgba(255,255,255,0.1);
            color: white;
            border: 1px solid var(--glass-border);
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        @media(min-width: 768px) {
            .menu-toggle {
                display: none;
            }
        }
        .sidebar a {
            color: #fff;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            display: block;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
        }
        .content {
            flex: 1;
            padding: 15px;
            background: transparent;
            width: 100%;
        }
        @media(min-width: 768px) {
            .content {
                padding: 30px;
            }
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0,0,0,0.2);
            min-width: 600px;
            color: var(--color-text);
        }
        th, td {
            padding: 12px;
            border: 1px solid var(--glass-border);
            text-align: left;
        }
        th { background: rgba(255,255,255,0.05); }

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
    </style>
</head>
<body style="background: linear-gradient(135deg, rgba(10, 10, 10, 0.9) 0%, rgba(30, 5, 5, 0.9) 100%); background-attachment: fixed; color: #fff;">
    <div class="cursor-dot" data-cursor-dot></div>
    <div class="cursor-outline" data-cursor-outline></div>
    <div class="admin-layout">
        @if(session('admin_id'))
        <div class="sidebar">
            <h2 style="font-family: var(--font-heading); margin-bottom: 30px;">Admin Panel</h2>
            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
            <a href="{{ url('/admin/lomba') }}">Master Lomba</a>
            <a href="{{ url('/admin/pendaftar') }}">Verifikasi Pendaftar</a>
            <a href="{{ url('/admin/peserta-lomba') }}">Daftar Peserta Lomba</a>
            <a href="{{ url('/admin/audit-logs') }}">Audit Trail</a>
            <div style="flex:1;"></div>
            <a href="{{ url('/admin/logout') }}" style="background: rgba(255,0,0,0.2); text-align:center;">Logout</a>
        </div>
        @endif
        
        <div class="content">
            @if(session('admin_id'))
            <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">☰ Menu Admin</button>
            @endif
            @yield('content')
        </div>
    </div>
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
        const hoverElements = document.querySelectorAll('a, button');
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
    </script>
</body>
</html>
