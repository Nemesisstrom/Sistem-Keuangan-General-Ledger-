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

Perintah tersebut menjalankan migration serta seeder yang terdaftar, termasuk data cabang dan bagan akun. Hindari `migrate:fresh` pada database yang berisi data penting karena perintah itu menghapus tabel sebelum membuatnya kembali.

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

Migration pengguna mendefinisikan peran berikut:

- `superadmin`
- `admin_branch`
- `accountant`
- `hr`

Nilai bawaan untuk peran pengguna adalah `accountant`. Pembatasan akses perlu diperiksa pada route dan controller yang digunakan; keberadaan peran pada database tidak dengan sendirinya menjamin seluruh akses sudah dibatasi sesuai peran.

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
