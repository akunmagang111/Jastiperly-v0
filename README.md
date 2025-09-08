# 🚀 Starter Project Laravel + Vite + Breeze

Starter kit Laravel dengan **Laravel Breeze (Auth)**, **role-based access** (`admin`, `finance`, `superadmin`), dan struktur dashboard per-role.


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## ✅ Prasyarat

- PHP **^8.1** (disarankan 8.2) + ekstensi umum (OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath)
- Composer **2.x**
- Node.js **^18** (atau 20 LTS) + NPM
- MySQL/MariaDB atau DB lain yang didukung

---

## 1) Clone Repository

```bash
git clone https://github.com/<username>/<repo>.git
cd <repo>
```

> Ganti `<username>/<repo>` dengan URL GitHub kamu.

---

## 2) Install Dependencies

```bash
composer install
npm install
```

---

## 3) Siapkan Environment

Salin file env & generate key:

```bash
cp .env.example .env
php artisan key:generate
```

Contoh `.env` (silakan sesuaikan):

```env
APP_NAME="Starter v1.2"
APP_ENV=local
APP_KEY=base64:***TERISI_OTOMATIS_SETELAH_KEY:GENERATE***
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starter_v12
DB_USERNAME=root
DB_PASSWORD=

# Cache / Session / Queue (default)
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail (opsional)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Vite
VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

**(Opsional)** Buat symlink storage:

```bash
php artisan storage:link
```

---

## 4) Migration & Seeder

```bash
php artisan migrate --seed
```

> Pastikan seeder membuat user & role (admin/finance/superadmin). Jika belum:
> - Buat user manual via `php artisan tinker` atau Register
> - Tambahkan kolom `role` pada tabel users (enum/string)
> - Set `role` user sesuai kebutuhan: `admin`, `finance`, atau `superadmin`.

---

## 5) Jalankan Aplikasi

Backend (Laravel):

```bash
php artisan serve
```

Frontend (Vite):

```bash
npm run dev
```

Akses:
- Laravel: **http://localhost:8000**
- Vite: **http://localhost:5173**

---

## 6) Routing & Role

Project memisahkan dashboard per-role:

- **Admin**:  
  - `/admin/dashboard` … `/admin/dashboard10`
  - Route names: `admin.index`, `admin.index2`, … `admin.index10`

- **Finance**:  
  - `/finance/dashboard` … `/finance/dashboard10`
  - Route names: `finance.index`, `finance.index2`, … `finance.index10`

- **Superadmin**:  
  - `/superadmin/dashboard` … `/superadmin/dashboard10`
  - Route names: `superadmin.index`, `superadmin.index2`, … `superadmin.index10`

### Helper untuk Sidebar (otomatis sesuai role)

Di Blade (mis. `components/sidebar.blade.php`) gunakan:

```php
@php
  $prefix = auth()->check() ? auth()->user()->role : 'guest';
  $name = fn($n) => $prefix.'.index'.($n === 1 ? '' : $n);
@endphp

<ul class="sidebar-submenu">
  <li><a href="{{ route($name(1)) }}">AI</a></li>
  <li><a href="{{ route($name(2)) }}">CRM</a></li>
  <li><a href="{{ route($name(3)) }}">eCommerce</a></li>
  <li><a href="{{ route($name(4)) }}">Cryptocurrency</a></li>
  <li><a href="{{ route($name(5)) }}">Investment</a></li>
  <li><a href="{{ route($name(6)) }}">LMS</a></li>
  <li><a href="{{ route($name(7)) }}">NFT & Gaming</a></li>
  <li><a href="{{ route($name(8)) }}">Medical</a></li>
  <li><a href="{{ route($name(9)) }}">Analytics</a></li>
  <li><a href="{{ route($name(10)) }}">POS & Inventory</a></li>
</ul>
```

> Pastikan user sudah login, jika belum login jangan render menu role.

---

## 7) Auth (Breeze) & Logout

- **Login page**: `GET /login`
- **Proses login**: `POST /login`
- **Logout** WAJIB `POST /logout` (bukan GET). Contoh button:

```blade
<form method="POST" action="{{ route('logout') }}">
  @csrf
  <button type="submit" class="dropdown-item text-danger">Log Out</button>
</form>
```

Jika klik logout diarahkan ke welcome, pastikan:
- Tidak ada route `/` yang override setelah login
- Middleware `auth` dan redirect role di `AuthenticationController@login` sudah sesuai

---

## 8) Build Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 9) Git & Remote

Inisialisasi Git (jika belum):

```bash
git init
```

Tambahkan remote:

```bash
git remote add origin https://github.com/<username>/<repo>.git
```

Simpan perubahan pertama:

```bash
git add .
git commit -m "Initial commit: Starter v1.2"
git branch -M main
git push -u origin main
```

---

## 10) Troubleshooting

- **`Route [index2] not defined`**  
  Pastikan:
  - Sidebar pakai nama route sesuai role (`admin.index2`, `finance.index2`, `superadmin.index2`) atau helper `$name(2)` di atas.
  - Route-group sudah di-load dan tidak di-comment.
  - Cache route belum “membeku”: jalankan `php artisan route:clear`.

- **`MethodNotAllowedHttpException` saat /logout**  
  Logout wajib `POST`. Pastikan tombol logout pakai `<form method="POST">` + `@csrf`.

- **Setelah zip & pindah server**  
  Tetap jalankan: `composer install`, `npm install`, `php artisan key:generate`, set `.env`, `php artisan migrate --seed`. 
  Jangan reinstall Breeze kalau sudah ada—cukup jalankan dependency & env saja.

---

## Struktur Direktori Penting

```
app/
└── Http/
│   └── Controllers/
│         └── _admin/
│             └── AdminDashboardController.php
│         ├── _finance/
│             └── FinanceDashboardController.php
│         └──_superadmin/
│             └── SuperadminDashboardController.php
resources/
└── views/
│   └── _admin/
│        └── dashboard/
│            └── index.blade.php
│            ├── index2.blade.php
│            ├── ...
│            └── index10.blade.php
│   ├── _finance/
│        └── dashboard/
│            └──index.blade.php
│            ├── index2.blade.php
│            ├── ...
│            └── index10.blade.php
    └── _superadmin/
│        └── dashboard/
│            └── index.blade.php
│            ├── index2.blade.php
│            ├── ...
│            └── index10.blade.php
routes/
└── web.php
```

---

## Lisensi

Tidak ditentukan.