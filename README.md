# TB Maju Jaya

Projek E-Commerce Toko Bahan Bangunan untuk pemenuhan tugas UAS Web Programming (IF330-I).

---

## Penjelasan Singkat

TB Maju Jaya adalah aplikasi toko online berbasis web yang memudahkan pelanggan dalam menjelajahi dan memesan bahan bangunan, serta membantu pengelola toko dalam mengelola produk, kategori, dan pesanan secara efisien.

### Fitur Utama

- **Katalog Produk & Kategori**: Pencarian produk, filter per kategori (semen, cat, pipa, perkakas, listrik, baja ringan), serta informasi ketersediaan stok dan harga per unit.
- **Keranjang Belanja & Checkout**: Pelanggan dapat mengatur jumlah pesanan di keranjang, memasukkan alamat pengiriman (radius layanan), dan memilih rekening pembayaran.
- **Transaksi & Pesanan**: Pelacakan status pesanan (Pending, Processing, Shipped, Completed, Cancelled), upload bukti transfer pembayaran, dan cetak faktur/invoice.
- **Panel Admin**: Dashboard ringkasan pesanan, manajemen data produk (tambah, edit, hapus, kelola foto), manajemen kategori, serta verifikasi pesanan dan bukti bayar.
- **Autentikasi & Otorisasi**: Sistem multi-peran (Admin dan Pelanggan) dengan proteksi rute middleware.

---

## Teknologi yang Digunakan

- **Backend**: PHP 8.1+, Laravel 10.x
- **Frontend**: Blade Templating, Bootstrap 5, Bootstrap Icons, JavaScript
- **Database**: SQLite (default) / MySQL
- **Build Tool**: Vite

---

## Prasyarat Sistem

Sebelum menjalankan proyek ini di komputer lokal, pastikan telah terpasang:

1. PHP >= 8.1 dengan ekstensi `pdo_sqlite` (atau `pdo_mysql` jika menggunakan MySQL), `mbstring`, `openssl`, `curl`.
2. Composer
3. Node.js (v18 ke atas direkomendasikan) & NPM

---

## Panduan Instalasi dan Penggunaan

Seluruh perintah dijalankan melalui terminal (PowerShell, Command Prompt, atau Terminal lain) pada direktori proyek `TB_Maju_Jaya`.

### 1. Masuk ke Direktori Proyek

```bash
cd TB_Maju_Jaya
```

### 2. Konfigurasi Environment (.env)

Salin file `.env.example` menjadi `.env`:

```bash
# Windows Command Prompt / PowerShell
copy .env.example .env
```

Secara default, konfigurasi menggunakan database SQLite (`DB_CONNECTION=sqlite`). Jika ingin menggunakan MySQL (misal melalui XAMPP atau Laragon), ubah konfigurasi pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tb_maju_jaya
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependensi Backend (Composer)

```bash
composer install
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Setup Database & Seeder

Jalankan migrasi tabel beserta data awal (kategori, produk contoh, akun admin & customer):

```bash
php artisan migrate --seed
```

> Jika menggunakan SQLite dan database belum dibuat, Laravel akan menanyakan apakah ingin membuat file database secara otomatis, pilih **Yes**.

### 6. Hubungkan Storage Publik

Perintah ini diperlukan agar gambar produk dan bukti pembayaran dapat diakses melalui browser:

```bash
php artisan storage:link
```

### 7. Install dan Kompilasi Asset Frontend

```bash
npm install
npm run build
```

*(Opsional untuk mode pengembangan: jalankan `npm run dev` pada terminal terpisah)*

### 8. Jalankan Server Lokal

```bash
php artisan serve
```

Aplikasi dapat diakses melalui browser di:
`http://127.0.0.1:8000` atau `http://localhost:8000`

---

## Akun Demo (Bawaan Seeder)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung masuk menggunakan akun default berikut:

| Peran | Email | Password | Akses |
|---|---|---|---|
| Admin | admin@majujaya.com | password | Panel Admin (`/admin`), Kelola Produk, Pesanan, Pelanggan |
| Customer | customer@majujaya.com | password | Belanja, Keranjang, Checkout, Riwayat Pesanan |

Anda juga dapat membuat akun pelanggan baru melalui menu **Daftar** di halaman login.

---

## Struktur Folder Utama

```text
TBmajuJaya/
├── README.md               # Dokumentasi utama proyek
└── TB_Maju_Jaya/           # Direktori aplikasi Laravel
    ├── app/
    │   ├── Http/Controllers/  # Controller Admin, Customer, & Auth
    │   └── Models/            # Model Eloquent (Product, Order, Category, User, dll)
    ├── config/shop.php        # Konfigurasi data toko (nama, rekening, WhatsApp)
    ├── database/
    │   ├── migrations/        # Skema migrasi database
    │   └── seeders/           # Seeder data pengguna, produk, & kategori
    ├── resources/
    │   └── views/             # Template tampilan Blade (admin, customer, auth, layouts)
    └── routes/
        └── web.php            # Routing web aplikasi
```
