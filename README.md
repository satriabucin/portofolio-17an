<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# 🇮🇩 Sistem Informasi Pendaftaran Lomba 17-an (Enterprise Edition)

[![CI/CD Status](https://img.shields.io/badge/CI%2FCD-Passing-brightgreen)](https://github.com/satriabucin/portofolio-17an/actions)
[![Security Rating](https://img.shields.io/badge/Security-A%2B-blue)](#)
[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red)](https://laravel.com)
[![React Version](https://img.shields.io/badge/React-18.x-blue)](https://reactjs.org)

Sistem pendaftaran dan manajemen acara tingkat RT/RW berstandar industri dengan pengamanan siber yang ketat, dikembangkan untuk merayakan kemerdekaan 17 Agustus. Dibangun dengan pendekatan **Mobile First**, **Clean Code Architecture**, dan **TDD (Test-Driven Development)**.

---

## 🌟 Fitur Utama (Core Features)

### 🧑‍💼 Modul Warga (Public)
- **Pendaftaran Otomatis:** Antarmuka responsif bagi warga untuk mendaftar multi-lomba dalam satu pendaftaran.
- **Cari Status & Unduh Tiket (Secured):** Warga dapat melacak status verifikasi pendaftaran menggunakan nomor HP.
- **E-Tiket dengan QR Code:** Unduhan otomatis tiket PDF.

### 🛡️ Modul Admin (Dashboard)
- **Manajemen Pendaftar:** Verifikasi, penyuntingan, dan hapus data warga secara aman (Anti-Mass Assignment).
- **Master Lomba:** Pembuatan dan pengacakan sistem bagan/sesi turnamen secara otomatis.
- **Audit Logs:** Perekaman aktivitas administrator untuk *traceability*.
- **Eksport Data (Streaming):** Ekspor rekapan data ke PDF dan Excel yang dioptimalkan untuk ratusan ribu data tanpa *Memory Exhaustion*.

---

## 🔒 Enterprise Security Standards

Proyek ini tidak hanya fungsional, tetapi didesain dengan ketahanan terhadap serangan siber kelas atas:
- **Anti-IDOR (Insecure Direct Object Reference):** Pengunduhan tiket warga dienkripsi menggunakan *Cryptographic Hash*.
- **Anti-Brute Force & DoS:** Titik masuk utama (Pendaftaran, Login, Cek Status) dilindungi oleh *Rate Limiting* (`throttle:5,1`).
- **Anti-Session Fixation:** ID Sesi (*Session ID*) selalu diregenerasi secara dinamis setiap sesi dimulai dan dihancurkan secara permanen saat *logout*.
- **Application DoS Prevention:** Fitur eksport menggunakan *Cursor/Streaming* untuk mencegah *Out-of-Memory (OOM)* pada ukuran database besar.
- **Centralized Middleware:** Penjagaan akses otentikasi di level *Route Group*.

---

## 🛠️ Stack Teknologi

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** React.js 18 + Inertia.js
- **Styling:** CSS Native + Glassmorphism UI
- **Database:** MySQL / SQLite (Testing)
- **Testing:** PHPUnit (Automated Unit/Feature Tests)
- **DevOps:** GitHub Actions (CI/CD to InfinityFree FTP)

---

## 🚀 Panduan Instalasi (Getting Started)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal Anda:

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM

### 1. Kloning Repositori
```bash
git clone https://github.com/satriabucin/portofolio-17an.git
cd portofolio-17an
```

### 2. Instalasi Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan
Salin file `.env.example` ke `.env` lalu sesuaikan konfigurasi *Database* Anda.
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi Database
```bash
php artisan migrate --seed
```

### 5. Jalankan Aplikasi
Buka 2 terminal secara terpisah:
```bash
# Terminal 1 (Backend)
php artisan serve

# Terminal 2 (Frontend)
npm run dev
```

Kunjungi `http://localhost:8000` di peramban Anda.

---

## 🧪 Panduan Pengujian (Testing)

Proyek ini mendukung **TDD (Test-Driven Development)**. Untuk memverifikasi keamanan dan fitur sistem, jalankan perintah:

```bash
php artisan test
```

*Seluruh pengujian dirancang untuk dijalankan dengan memori sementara (SQLite in-memory) sehingga tidak akan merusak data MySQL Anda.*

---

<p align="center">
  Dibuat dengan ❤️ untuk Indonesia
</p>
