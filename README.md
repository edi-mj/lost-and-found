# Lost & Found Application

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/Redis-3.3-DC382D?style=for-the-badge&logo=redis&logoColor=white" alt="Redis">
  <img src="https://img.shields.io/badge/JWT-6.11-000000?style=for-the-badge&logo=jsonwebtokens&logoColor=white" alt="JWT">
</p>

## Deskripsi Proyek

Aplikasi web untuk membantu pengguna melaporkan dan mencari barang hilang atau ditemukan. Sistem ini dibangun menggunakan Laravel sebagai frontend dan terintegrasi dengan backend microservices melalui REST API. Fitur utama meliputi pelaporan barang hilang/ditemukan, pencarian barang, verifikasi kepemilikan, dan notifikasi real-time.

### Note

Proyek ini dibangun untuk keperluan tugas akhir mata kuliah Sistem Teristribusi. Hingga saat ini project ini hanya memenuhi fungsionalitas dasar dan belum selesai sepenuhnya.

## Fitur Utama

### Untuk Pengguna

-   **Autentikasi & Otorisasi** - Registrasi dan login dengan JWT token
-   **Laporan Kehilangan** - Laporkan barang yang hilang dengan deskripsi dan foto
-   **Laporan Penemuan** - Laporkan barang yang ditemukan untuk membantu orang lain
-   **Pencarian Cerdas** - Cari barang berdasarkan kata kunci, lokasi, atau kategori
-   **Verifikasi Kepemilikan** - Sistem verifikasi untuk klaim barang
-   **Notifikasi Real-time** - Dapatkan notifikasi saat ada match atau update status
-   **Dashboard Pribadi** - Kelola semua laporan Anda dalam satu tempat

### Untuk Admin

-   **Dashboard Admin** - Panel kontrol untuk monitoring sistem
-   **Manajemen Verifikasi** - Review dan approve klaim kepemilikan
-   **Moderasi Konten** - Kelola laporan yang masuk

## Tech Stack

### Backend & Framework

-   **Laravel 12** - PHP framework untuk routing, middleware, dan view rendering
-   **PHP 8.2+** - Server-side scripting language
-   **Laravel Sanctum** - API token authentication
-   **Firebase JWT** - JSON Web Token untuk autentikasi microservices

### Frontend & UI

-   **Blade Templates** - Laravel templating engine
-   **TailwindCSS 3** - Utility-first CSS framework
-   **Tailwind Forms** - Form styling plugin
-   **Vite 7** - Modern frontend build tool
-   **Axios** - HTTP client untuk API calls

### Database & Cache

-   **MySQL/PostgreSQL** - Relational database (sesuai konfigurasi)
-   **Redis (Predis)** - In-memory data store untuk caching dan queues

## Arsitektur Microservices

Aplikasi ini menggunakan arsitektur microservices dengan Laravel sebagai API Gateway dan Frontend. Backend services terpisah menangani:

-   **User Management Service** - Autentikasi dan manajemen pengguna
-   **Reports Service** - Pengelolaan laporan kehilangan dan penemuan
-   **Search Service** - Pencarian dan filtering barang
-   **Verification Service** - Verifikasi kepemilikan barang
-   **Notification Service** - Pengiriman notifikasi

Komunikasi antar service menggunakan REST API dengan JWT authentication.

## Instalasi & Setup

### Prasyarat

-   PHP 8.2 atau lebih tinggi
-   Composer
-   Node.js & NPM
-   MySQL atau PostgreSQL
-   Redis (optional, untuk caching)

### Quick Start

1. **Clone repository**

```bash
git clone https://github.com/edi-mj/lost-and-found.git
cd lost-and-found
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Setup environment**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database & services di `.env`**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lost_and_found
DB_USERNAME=root
DB_PASSWORD=

# Microservices API Endpoints
API_USERS_MANAGEMENT=http://localhost:3001/api
API_REPORTS=http://localhost:3002/api
API_SEARCH_MANAGEMENT=http://localhost:3003/api
API_VERIFICATION=http://localhost:3004/api
API_NOTIFICATION=http://localhost:3005/api

# JWT Secret (harus sama dengan Express backend)
EXPRESS_JWT_SECRET=your-secret-key
```

5. **Jalankan migrasi database**

```bash
php artisan migrate
```

6. **Build assets**

```bash
npm run build
```

7. **Jalankan development server**

```bash
# Otomatis menjalankan Laravel server, queue worker, logs, dan Vite
composer dev
```

Atau jalankan secara manual:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev

# Terminal 3 (optional - untuk queue)
php artisan queue:listen
```

## Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/      # Request handlers
│   └── Middleware/       # JWT verification, admin check
├── Models/              # Eloquent models
└── Services/            # Business logic (NotificationService)

database/
├── migrations/          # Database schema
└── seeders/            # Data seeding

resources/
├── views/              # Blade templates
│   ├── auth/          # Login & register
│   ├── reports/       # Report pages
│   ├── search/        # Search interface
│   └── admin/         # Admin dashboard
└── css/ & js/         # Frontend assets

routes/
├── web.php            # Web routes
└── api.php            # API routes

config/
└── services.php       # Microservices endpoints configuration
```

## Testing

```bash
# Jalankan semua test
composer test

# Atau
php artisan test

# Test dengan coverage
php artisan test --coverage
```

## Code Style

Proyek ini menggunakan Laravel Pint untuk menjaga konsistensi code style:

```bash
./vendor/bin/pint
```

## API Integration

Aplikasi ini berkomunikasi dengan microservices backend menggunakan HTTP client. Contoh endpoint yang digunakan:

-   `POST /api/auth/login` - User authentication
-   `GET /api/reports` - Fetch reports
-   `POST /api/reports` - Create new report
-   `GET /api/search` - Search items
-   `POST /api/verifications` - Submit verification request
-   `GET /api/notifications` - Get user notifications

JWT token dari Express backend diverifikasi menggunakan middleware `VerifyJwtFromExpress`.

## Kontribusi

Proyek ini dibangun untuk keperluan tugas akhir mata kuliah Sistem Terdistribusi. Kontribusi selalu terbuka! Silakan fork repository ini dan submit pull request untuk perbaikan atau fitur baru.
