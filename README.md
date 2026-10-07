# Sistem General Ledger dan Keuangan Perusahaan

Aplikasi pencatatan keuangan berbasis Laravel untuk mendukung pengelolaan jurnal umum, bagan akun, dan laporan keuangan. Aplikasi menggunakan metode pencatatan berpasangan (*double-entry bookkeeping*) dengan dukungan data cabang.

## Fitur

Fitur antarmuka web yang tersedia:

- Dashboard ringkasan keuangan dan transaksi jurnal terbaru.
- Pencatatan jurnal dengan beberapa baris akun dan pemeriksaan keseimbangan debit-kredit.
- Pengelolaan bagan akun, termasuk penambahan, perubahan, penelusuran, dan penghapusan akun yang belum digunakan.
- Laporan buku besar, laba rugi, dan neraca.
- Autentikasi pengguna dan pemilihan cabang pada pencatatan jurnal.

Repositori juga memuat controller dan service untuk beberapa proses lain, seperti payroll, pajak, karyawan, dan absensi. Ketersediaan proses tersebut melalui antarmuka web dapat berbeda; periksa route dan implementasi terkait sebelum menggunakannya.

## Teknologi

- PHP `^8.2`
- Laravel `^12.0`
- Livewire `^4.4`
- Tailwind CSS `^4.0`
- Vite `^7.0`
- MySQL atau MariaDB

## Persyaratan

- PHP 8.2 atau versi yang kompatibel dengan dependensi Laravel.
- Composer.
- Node.js yang didukung Vite 7 dan npm.
- MySQL atau MariaDB.
- Ekstensi PHP yang dibutuhkan Laravel, termasuk `ctype`, `fileinfo`, `mbstring`, `openssl`, `pdo`, dan `tokenizer`.

## Instalasi

Jalankan perintah berikut dari direktori proyek:

```bash
composer install
npm install
```

Siapkan konfigurasi aplikasi:

```bash
cp .env.example .env
php artisan key:generate
```

Pada Windows PowerShell, file `.env.example` dapat disalin dengan perintah berikut:

```powershell
Copy-Item .env.example .env
```

Buat database, kemudian sesuaikan konfigurasi berikut pada file `.env`:

```dotenv
APP_NAME="General Ledger"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=keuangan_db
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Perintah tersebut menjalankan migration serta seeder yang terdaftar, termasuk data cabang, bagan akun, dan role/permission. Pengguna yang mendaftar melalui aplikasi otomatis mendapat role `Staff`.

Untuk membuat akun administrator awal, isi `SEED_ADMIN_EMAIL` dan `SEED_ADMIN_PASSWORD` pada `.env` sebelum menjalankan seeder. Kata sandi administrator harus minimal 12 karakter. Kedua nilai harus diisi bersama; jika tidak, seeder hanya membuat role dan permission tanpa akun dengan kata sandi bawaan.

Hindari `migrate:fresh` pada database yang berisi data penting karena perintah itu menghapus tabel sebelum membuatnya kembali.

Bangun aset frontend dan jalankan server:

```bash
npm run build
php artisan serve
```

Buka alamat yang ditampilkan oleh `php artisan serve`, biasanya `http://127.0.0.1:8000`.

Untuk pengembangan frontend, jalankan Vite pada terminal terpisah:

```bash
npm run dev
```

## Pengujian

Jalankan seluruh pengujian dengan:

```bash
php artisan test
```

Pengujian database pada konfigurasi bawaan menggunakan SQLite dalam memori. Pastikan ekstensi PDO SQLite tersedia jika ingin menjalankan pengujian fitur yang memerlukan database.

## Peran Pengguna

Migration pengguna mendefinisikan kategori pengguna berikut:

- `admin`
- `admin_branch`
- `accountant`
- `hr`

Nilai bawaan kategori pengguna adalah `accountant`. Otorisasi aplikasi menggunakan role dan permission Spatie: `Admin` dapat mengelola akun dan pengguna, sedangkan `Staff` dapat melihat laporan dan menggunakan fitur jurnal.

## Struktur Direktori

```text
app/
  Http/Controllers/   Controller web dan endpoint aplikasi
  Http/Middleware/    Middleware aplikasi, termasuk pemeriksaan peran
  Livewire/           Komponen antarmuka interaktif
  Models/             Model Eloquent
  Services/           Logika bisnis dan pemrosesan transaksi
  Traits/             Trait model, termasuk dukungan cabang
database/
  migrations/         Definisi skema database
  seeders/            Data awal cabang, akun, dan pajak
resources/
  css/                Sumber CSS Tailwind
  js/                 Sumber JavaScript frontend
  views/              Template Blade aplikasi
routes/
  web.php             Route antarmuka web
tests/
  Feature/            Pengujian fitur
  Unit/               Pengujian unit
```
