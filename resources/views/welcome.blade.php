<x-layout>
    <style>
        /* CSS Untuk Animasi Dramatis & Layout Premium */
        body {
            background-color: #0a0a0a;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        .hero {
            /* Menggunakan gambar user sebagai background hero dengan overlay gelap */
            background: linear-gradient(135deg, rgba(10, 10, 10, 0.9) 0%, rgba(30, 5, 5, 0.8) 100%), url('{{ asset("images/IMG_2704.JPG") }}') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            padding: 140px 0 100px 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        #particles-js {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
            width: 100%;
        }

        .hero-text {
            flex: 1 1 400px;
            max-width: 650px;
        }

        .hero-text h1 {
            font-size: clamp(3.5rem, 8vw, 5.5rem);
            line-height: 1.05;
            margin-bottom: 20px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -1.5px;
            text-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .hero-text p {
            font-size: clamp(1.1rem, 2vw, 1.25rem);
            color: rgba(255,255,255,0.8);
            margin-bottom: 40px;
            max-width: 500px;
            line-height: 1.6;
        }

        .hero-visual {
            flex: 1 1 350px;
            position: relative;
        }

        /* Squarespace Style Carousel */
        .ss-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 3;
        }

        .ss-carousel {
            position: relative;
            aspect-ratio: 4/5;
            overflow: hidden;
            border-radius: 2px;
            background: #eee;
        }

        .ss-slide {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(1.05);
        }

        .ss-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .ss-slide img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .ss-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding: 0 5px;
            color: #111;
        }

        /* Animasi Dramatis Entrance */
        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            animation: fadeUpAnim 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        @keyframes fadeUpAnim {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navbar Override for Dark Theme */
        .navbar {
            background: rgba(17, 17, 17, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .navbar-brand, .navbar a {
            color: #fff !important;
        }

        /* Mobile Adjustments */
        @media(max-width: 768px) {
            .hero {
                padding: 80px 0 40px 0;
                align-items: flex-start;
            }
            .hero-content {
                flex-direction: column;
                gap: 20px; /* Jarak yang pas, tidak terlalu mepet */
            }
            .hero-text {
                text-align: left;
                flex-basis: auto; /* Penting: mencegah elemen ini mengambil tinggi 400px di layar HP */
            }
            .hero-text h1 {
                font-size: clamp(2.8rem, 12vw, 3.5rem);
                margin-bottom: 0px;
            }
            .hero-visual {
                width: 100%;
                flex-basis: auto;
            }
            .ss-card {
                padding: 10px;
                margin-bottom: 15px !important;
            }
            .ss-carousel {
                aspect-ratio: 3/4; /* Membuat fotonya lebih tinggi/proporsional di layar HP */
            }
        }

        /* Live Feed Toast */
        .live-feed-toast {
            position: fixed;
            bottom: 30px;
            left: -350px;
            width: 320px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            padding: 15px 20px;
            border-radius: 12px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            transition: left 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        .live-feed-toast.show {
            left: 30px;
        }
        .live-feed-icon {
            background: var(--color-primary);
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            animation: pulse-icon 2s infinite;
        }
        @keyframes pulse-icon {
            0% { box-shadow: 0 0 0 0 rgba(255, 71, 71, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 71, 71, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 71, 71, 0); }
        }
    </style>

    <div class="hero">
        <div id="particles-js"></div>
        <div class="container hero-content">
            
            <!-- Kiri: Teks -->
            <div class="hero-text">
                <p class="fade-up" style="color: #ff4747; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px; font-size: 0.9rem;">Peringatan HUT RI</p>
                <h1 class="fade-up delay-100">
                    Rayakan Keseruan<br>
                    <span style="color: #ff4747;">17 Agustus</span><br>
                    Bersama Kami.
                </h1>
            </div>

            <!-- Kanan: Carousel Interaktif -->
            <div class="hero-visual fade-up delay-300" id="galeri">
                <div class="ss-card" style="margin-bottom: 25px;">
                    <div class="ss-carousel" id="heroCarousel">
                        <div class="ss-slide active">
                            <img src="{{ asset('images/IMG_2443.JPG') }}" alt="Foto Acara 1">
                        </div>
                        <div class="ss-slide">
                            <img src="{{ asset('images/IMG_2550.JPG') }}" alt="Foto Acara 2">
                        </div>
                        <div class="ss-slide">
                            <img src="{{ asset('images/IMG_2582.JPG') }}" alt="Foto Acara 3">
                        </div>
                        <div class="ss-slide">
                            <img src="{{ asset('images/IMG_2817.JPG') }}" alt="Foto Acara 4">
                        </div>
                        <div class="ss-slide">
                            <img src="{{ asset('images/IMG_2892.JPG') }}" alt="Foto Acara 5">
                        </div>
                    </div>
                    <div class="ss-nav">
                        <div style="font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #fff;">Galeri Acara</div>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="moveSsSlide(-1)" style="background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); width: 35px; height: 35px; border-radius: 50%; cursor: pointer; transition: 0.3s; display:flex; align-items:center; justify-content:center;">❮</button>
                            <button onclick="moveSsSlide(1)" style="background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); width: 35px; height: 35px; border-radius: 50%; cursor: pointer; transition: 0.3s; display:flex; align-items:center; justify-content:center;">❯</button>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Daftar Sekarang dipindah ke bawah carousel -->
                <div class="fade-up delay-400" style="display: flex; justify-content: center; width: 100%;">
                    <a href="{{ url('/daftar') }}" class="btn btn-glow" style="background: var(--color-primary); color: #fff; padding: 15px 30px; font-weight: bold; font-size: 1.1rem; border-radius: 4px; width: 100%; text-align: center; text-decoration: none;">DAFTAR SEKARANG</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Seksi Fitur (Opsional) -->
    <section style="background: #fff; color: #111; padding: 80px 0;">
        <div class="container">
            <h2 style="font-size: 2.5rem; margin-bottom: 40px; color: #111; text-align: center;">Kenapa Harus Join?</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div style="padding: 30px; background: #fafafa; border-radius: 8px;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 15px;">Daftar Hitungan Detik</h3>
                    <p style="color: #666; line-height: 1.6;">Tinggalkan cara lama. Buka web via HP, isi data, beres.</p>
                </div>
                <div style="padding: 30px; background: #fafafa; border-radius: 8px;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 15px;">Pantau Real Time</h3>
                    <p style="color: #666; line-height: 1.6;">Cek jadwal lomba lengkap dan susunan acara secara langsung dari HP kamu.</p>
                </div>
                <div style="padding: 30px; background: #fafafa; border-radius: 8px;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 15px;">Simpan Kenangan</h3>
                    <p style="color: #666; line-height: 1.6;">Semua momen keseruan masuk galeri publik. Kamu bisa buka dan kenang kapan pun.</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Logika Carousel Squarespace
        let ssIndex = 0;
        const ssSlides = document.querySelectorAll('.ss-slide');
        let autoPlayTimer;

        function showSsSlide(n) {
            ssSlides.forEach(slide => slide.classList.remove('active'));
            ssIndex = (n + ssSlides.length) % ssSlides.length;
            ssSlides[ssIndex].classList.add('active');
        }

        function moveSsSlide(n) {
            showSsSlide(ssIndex + n);
            resetAutoPlay();
        }

        function resetAutoPlay() {
            clearInterval(autoPlayTimer);
            autoPlayTimer = setInterval(() => {
                moveSsSlide(1);
            }, 4000);
        }

        // Swipe support untuk HP
        let touchstartX = 0;
        let touchendX = 0;
        const carouselEl = document.getElementById('heroCarousel');

        carouselEl.addEventListener('touchstart', e => {
            touchstartX = e.changedTouches[0].screenX;
        });

        carouselEl.addEventListener('touchend', e => {
            touchendX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            if (touchendX < touchstartX - 40) moveSsSlide(1); // Swipe left
            if (touchendX > touchstartX + 40) moveSsSlide(-1); // Swipe right
        }

        // Mulai autoplay
        resetAutoPlay();
    </script>
</x-layout>
