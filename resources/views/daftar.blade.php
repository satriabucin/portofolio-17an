<x-layout title="Daftar Lomba">
    <style>
        /* Wizard Progress Bar */
        .wizard-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 40px;
        }
        .wizard-progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--glass-bg);
            z-index: 1;
            transform: translateY(-50%);
        }
        .wizard-progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--color-primary);
            z-index: 2;
            transform: translateY(-50%);
            transition: width 0.4s ease;
            box-shadow: 0 0 10px var(--color-primary);
        }
        .wizard-step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--color-background);
            border: 2px solid var(--glass-border);
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text);
            font-weight: bold;
            transition: all 0.4s ease;
        }
        .wizard-step-dot.active {
            background: var(--color-primary);
            border-color: var(--color-primary);
            box-shadow: 0 0 15px rgba(255, 71, 71, 0.5);
        }
        .wizard-step-dot.completed {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }

        /* Form Sections */
        .form-section {
            display: none;
            animation: fadeIn 0.5s;
        }
        .form-section.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Custom Checkbox for Lomba */
        .lomba-label {
            display: block; 
            background: var(--glass-bg); 
            padding: 20px; 
            border-radius: 12px; 
            margin-bottom: 15px; 
            cursor: pointer; 
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .lomba-label:hover {
            border-color: rgba(255, 71, 71, 0.5);
            background: rgba(255, 71, 71, 0.05);
            transform: translateY(-2px);
        }
        .lomba-label input[type="checkbox"] {
            display: none;
        }
        .lomba-label input[type="checkbox"]:checked + .lomba-content {
            border-color: var(--color-primary);
        }
        .lomba-label input[type="checkbox"]:checked ~ .check-indicator {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }
        .lomba-label input[type="checkbox"]:checked ~ .check-indicator::after {
            display: block;
        }
        .check-indicator {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--glass-border);
            transition: all 0.3s ease;
        }
        .check-indicator::after {
            content: '';
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 6px;
            height: 12px;
            border: solid var(--color-primary);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>

    <div class="container" style="padding-top: 40px; padding-bottom: 80px;">
        <div style="margin-bottom: 20px;">
            <a href="{{ url('/') }}" class="btn" style="background: var(--glass-bg); color: var(--color-text); border: 1px solid var(--glass-border); padding: 8px 15px;">&larr; Kembali ke Beranda</a>
        </div>
        
        <div class="glass-card" style="max-width: 650px; margin: 0 auto; padding: 50px 40px;">
            <h1 style="text-align: center; margin-bottom: 10px; color: var(--color-text); font-weight: 800;">Formulir Pendaftaran</h1>
            <p style="text-align: center; color: var(--color-text); opacity: 0.6; margin-bottom: 40px;">Ikuti 3 langkah mudah untuk bergabung dalam keseruan 17-an.</p>

            @if(session('success'))
                <div style="background: rgba(40, 167, 69, 0.2); color: #4ade80; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(40, 167, 69, 0.3); text-align: center; font-weight: bold;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(220, 53, 69, 0.4); text-align: center; font-weight: bold;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(220, 53, 69, 0.4); text-align: left; font-size: 0.9rem;">
                    <strong style="display: block; margin-bottom: 5px;">Mohon periksa kembali isian Anda:</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="wizard-progress">
                <div class="wizard-progress-bar" id="progress-bar"></div>
                <div class="wizard-step-dot active" id="dot-1">1</div>
                <div class="wizard-step-dot" id="dot-2">2</div>
                <div class="wizard-step-dot" id="dot-3">3</div>
            </div>

            <form action="{{ url('/daftar') }}" method="POST" id="wizard-form">
                @csrf
                
                <!-- STEP 1: Data Diri -->
                <div class="form-section active" id="step-1">
                    <h3 style="margin-bottom: 25px; color: var(--color-primary); font-size: 1.3rem;">Langkah 1: Data Diri</h3>
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="nama" style="display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text); padding: 15px; border-radius: 8px;" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="form-group" style="margin-bottom: 30px;">
                        <label for="no_hp" style="display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nomor HP / WhatsApp</label>
                        <input type="tel" id="no_hp" name="no_hp" class="form-control" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text); padding: 15px; border-radius: 8px;" required placeholder="Contoh: 08123456789">
                    </div>
                    <div style="text-align: right;">
                        <button type="button" class="btn btn-primary" onclick="nextStep(1)" style="padding: 12px 30px; border-radius: 8px;">Selanjutnya &rarr;</button>
                    </div>
                </div>

                <!-- STEP 2: Alamat -->
                <div class="form-section" id="step-2">
                    <h3 style="margin-bottom: 25px; color: var(--color-primary); font-size: 1.3rem;">Langkah 2: Alamat Tinggal</h3>
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="blok_rumah" style="display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Blok / Nomor Rumah</label>
                        <input type="text" id="blok_rumah" name="blok_rumah" class="form-control" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text); padding: 15px; border-radius: 8px;" required placeholder="Contoh: Blok A No. 12">
                    </div>
                    <div class="form-group" style="margin-bottom: 30px;">
                        <label for="rt" style="display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">RT (Rukun Tetangga)</label>
                        <select id="rt" name="rt" class="form-control" style="background: var(--color-background); border: 1px solid var(--glass-border); color: var(--color-text); padding: 15px; border-radius: 8px;" required>
                            <option value="">-- Pilih RT --</option>
                            <option value="RT 01">RT 01</option>
                            <option value="RT 02">RT 02</option>
                            <option value="RT 03">RT 03</option>
                            <option value="RT 04">RT 04</option>
                        </select>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <button type="button" class="btn" onclick="prevStep(2)" style="background: var(--glass-bg); color: var(--color-text); padding: 12px 25px; border-radius: 8px;">&larr; Kembali</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)" style="padding: 12px 30px; border-radius: 8px;">Selanjutnya &rarr;</button>
                    </div>
                </div>

                <!-- STEP 3: Lomba -->
                <div class="form-section" id="step-3">
                    <h3 style="margin-bottom: 10px; color: var(--color-primary); font-size: 1.3rem;">Langkah 3: Pilihan Lomba</h3>
                    <p style="color: var(--color-text); opacity: 0.7; font-size: 0.9rem; margin-bottom: 25px;">Anda dapat mengikuti lebih dari satu lomba.</p>
                    
                    <div style="max-height: 400px; overflow-y: auto; padding-right: 10px; margin-bottom: 30px;">
                        @forelse($lombas as $lomba)
                        <label class="lomba-label">
                            <input type="checkbox" name="lombas[]" value="{{ $lomba->id }}">
                            <div class="lomba-content">
                                <strong style="color: var(--color-text); font-size: 1.1rem; display: block; margin-bottom: 5px;">{{ $lomba->nama_lomba }}</strong>
                                <span style="display: inline-block; background: rgba(255, 71, 71, 0.2); color: #ff4747; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; margin-bottom: 8px;">{{ $lomba->kategori_usia }}</span>
                                @if($lomba->kuota)
                                <span style="display: inline-block; background: var(--glass-bg); color: var(--color-text); opacity: 0.8; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; margin-bottom: 8px; margin-left: 5px;">Sisa Kuota: {{ $lomba->kuota - DB::table('pendaftar_lomba')->where('id_lomba', $lomba->id)->count() }}</span>
                                @endif
                                
                                <div style="margin-bottom: 8px; display: flex; flex-direction: column; gap: 4px; font-size: 0.85rem; color: var(--color-text); opacity: 0.9;">
                                    @if($lomba->jadwal_waktu)
                                    <div><strong style="color: var(--color-primary);">📅</strong> {{ date('d M Y, H:i', strtotime($lomba->jadwal_waktu)) }}</div>
                                    @endif
                                    @if($lomba->lokasi)
                                    <div><strong style="color: var(--color-primary);">📍</strong> {{ $lomba->lokasi }}</div>
                                    @endif
                                </div>

                                <p style="color: var(--color-text); opacity: 0.6; font-size: 0.9rem; margin: 0; line-height: 1.4;">{{ $lomba->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            <div class="check-indicator"></div>
                        </label>
                        @empty
                        <div style="text-align: center; padding: 30px; background: var(--glass-bg); border-radius: 8px;">
                            <p style="color: var(--color-text); opacity: 0.6; margin: 0;">Belum ada lomba yang dibuka.</p>
                        </div>
                        @endforelse
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <button type="button" class="btn" onclick="prevStep(3)" style="background: var(--glass-bg); color: var(--color-text); padding: 12px 25px; border-radius: 8px;">&larr; Kembali</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary btn-glow" style="padding: 12px 30px; border-radius: 8px; font-weight: bold; border: none;"><span class="btn-text">Selesai & Daftar</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function nextStep(currentStep) {
            // Simple Validation
            if(currentStep === 1) {
                if(!document.getElementById('nama').value || !document.getElementById('no_hp').value) {
                    alert("Mohon lengkapi Nama dan Nomor HP terlebih dahulu.");
                    return;
                }
            } else if(currentStep === 2) {
                if(!document.getElementById('blok_rumah').value || !document.getElementById('rt').value) {
                    alert("Mohon lengkapi Blok Rumah dan RT terlebih dahulu.");
                    return;
                }
            }

            document.getElementById('step-' + currentStep).classList.remove('active');
            document.getElementById('step-' + (currentStep + 1)).classList.add('active');
            
            updateProgress(currentStep + 1);
        }

        function prevStep(currentStep) {
            document.getElementById('step-' + currentStep).classList.remove('active');
            document.getElementById('step-' + (currentStep - 1)).classList.add('active');
            
            updateProgress(currentStep - 1);
        }

        function updateProgress(step) {
            const progressBar = document.getElementById('progress-bar');
            
            // Update Bar Width
            if(step === 1) progressBar.style.width = '0%';
            else if(step === 2) progressBar.style.width = '50%';
            else if(step === 3) progressBar.style.width = '100%';

            // Update Dots
            for(let i = 1; i <= 3; i++) {
                const dot = document.getElementById('dot-' + i);
                if(i < step) {
                    dot.classList.add('completed');
                    dot.classList.remove('active');
                } else if(i === step) {
                    dot.classList.add('active');
                    dot.classList.remove('completed');
                } else {
                    dot.classList.remove('active', 'completed');
                }
            }
        }

        // Loading Spinner Form Submit
        document.getElementById('formDaftar').addEventListener('submit', function(e) {
            const btn = document.getElementById('btnSubmit');
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner-modern"></span><span class="btn-text">Memproses...</span>';
        });
    </script>

    @if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };

            function randomInRange(min, max) {
              return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
              var timeLeft = animationEnd - Date.now();

              if (timeLeft <= 0) {
                return clearInterval(interval);
              }

              var particleCount = 50 * (timeLeft / duration);
              // Confetti Merah Putih
              confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }, colors: ['#ff0000', '#ffffff'] }));
              confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }, colors: ['#ff0000', '#ffffff'] }));
            }, 250);
        });
    </script>
    @endif
</x-layout>




