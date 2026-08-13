<x-layout>
    <x-slot:title>Galeri Kegiatan 17 Agustus</x-slot>

    <style>
        .gallery-header {
            text-align: center;
            padding: 20px 20px 40px;
            background: linear-gradient(180deg, rgba(10,10,10,0) 0%, rgba(10,10,10,1) 100%);
        }
        
        .gallery-title {
            font-size: clamp(2.5rem, 8vw, 4rem);
            font-weight: 800;
            color: #fff;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .gallery-title span {
            color: var(--color-primary);
        }

        .gallery-desc {
            color: #ccc;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto 80px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 4/3;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .gallery-item::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item:hover::after {
            opacity: 1;
        }

        .gallery-item-overlay {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 2;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .gallery-item:hover .gallery-item-overlay {
            transform: translateY(0);
            opacity: 1;
        }
        
        .gallery-tag {
            display: inline-block;
            background: var(--color-primary);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        /* Modal Lightbox */
        .lightbox {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .lightbox.active {
            display: flex;
            opacity: 1;
        }
        
        .lightbox img {
            max-width: 90%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .lightbox-close {
            position: absolute;
            top: 30px; right: 30px;
            color: #fff;
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .lightbox-close:hover {
            color: var(--color-primary);
        }
    </style>

    <div style="max-width: 1400px; margin: 0 auto; padding: 40px 20px 0; text-align: left;">
        <a href="{{ url('/') }}" class="btn" style="background: rgba(255,255,255,0.1); color: #fff; padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); text-decoration: none; display: inline-block; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">&larr; Kembali ke Beranda</a>
    </div>

    <div class="gallery-header fade-up">
        <h1 class="gallery-title">Galeri <span>Keseruan</span></h1>
        <p class="gallery-desc">Momen-momen tak terlupakan dari perayaan kemerdekaan 17 Agustus tahun ini. Penuh tawa, perjuangan, dan kebersamaan warga.</p>
    </div>
    
    <div class="gallery-grid fade-up delay-200">
        <?php
            $imageFiles = glob(public_path('images/*.JPG'));
            $images = [];
            foreach($imageFiles as $file) {
                $images[] = basename($file);
            }
        ?>
        
        @forelse($images as $index => $img)
        <div class="gallery-item" onclick="openLightbox('{{ asset('images/' . $img) }}')">
            <img src="{{ asset('images/' . $img) }}" loading="lazy" alt="Dokumentasi 17an">
            <div class="gallery-item-overlay">
                <span class="gallery-tag">Momen 17an</span>
                <h3 style="color: #fff; margin: 0; font-size: 1.2rem;">Dokumentasi #{{ $index + 1 }}</h3>
            </div>
        </div>
        @empty
        <div class="glass-card" style="grid-column: 1 / -1; height: 300px; display:flex; align-items:center; justify-content:center; color:#aaa; font-weight:bold; text-align: center;">
            Belum Ada Dokumentasi Foto
        </div>
        @endforelse
    </div>

    <!-- Lightbox Element -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <img src="" id="lightbox-img" alt="Zoomed Image">
    </div>

    <script>
        function openLightbox(src) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            lightboxImg.src = src;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
        
        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }
    </script>
</x-layout>
