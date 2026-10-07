<div align="center">

# 📑 General Ledger & Enterprise Financial System
**A High-Precision, Multi-Branch Double-Entry Bookkeeping & Financial Management Engine**

[![Laravel](https://img.shields.io/badge/Laravel-v12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-v3.x-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

[Architecture](#-system-architecture) • [Features](#-core-capabilities) • [Installation](#-getting-started) • [Database Schema](#-financial-logic--schema) • [Testing](#-code-quality--testing)

</div>

---

## 📌 Executive Summary

**General Ledger & Enterprise Financial System** adalah platform pengelolaan keuangan terdistribusi berbasis **Laravel 12** dan **Livewire**. Platform ini dirancang khusus untuk memenuhi standar akuntansi komersial dengan penekanan pada **pencatatan ganda (double-entry bookkeeping)**, **isolasi data antar-cabang (multi-branch tenancy)**, serta **otomatisasi payroll dan pelaporan keuangan real-time**.

---

## 🚀 Core Capabilities

### 🏢 1. Multi-Branch Data Isolation
- **Tenant Scope Isolation**: Menggunakan `BranchScope` global untuk memastikan transaksi keuangan cabang A terisolasi sepenuhnya dari cabang B.
- **Role-Based Access Control (RBAC)**: Pembatasan hak akses berbasis peran (Admin Utama, Branch Manager, Accountant, HR Staff).

### ⚖️ 2. High-Precision Double-Entry Ledger
- **Strict Debit-Credit Balancing**: Otomatisasi validasi transaksi di mana $\sum \text{Debit} = \sum \text{Kredit}$.
- **Immutable Transaction Records**: Setiap perubahan entri jurnal menggunakan mekanisme audit trail berbasis log transaksi.
- **Flexible Chart of Accounts (COA)**: Hierarki akun modular mencakup *Assets*, *Liabilities*, *Equity*, *Revenues*, dan *Expenses*.

### 💼 3. Automated HR & Payroll Ledger
- **Attendance-to-Payroll Pipeline**: Kalkulasi otomatis komponen gaji pokok, potongan absensi, dan PPh 21 dari log kehadiran.
- **Auto Journal Posting**: Hasil kalkulasi payroll otomatis memicu entri jurnal beban gaji (*Payroll Expenses*) ke general ledger.

### 📊 4. Real-Time Financial Statements
- **General Ledger (Buku Besar)**: Filtering detail entri per periode dan per akun.
- **Trial Balance (Neraca Saldo)**: Rekapitulasi saldo awal, pergerakan mutasi, dan saldo akhir.
- **Profit & Loss (Laba Rugi)**: Penghitungan pendapatan operasional bersih.
- **Balance Sheet (Neraca)**: Ringkasan posisi aset, kewajiban, dan modal perusahaan.

---

## 📐 System Architecture

```mermaid
graph TD
    User([User / Accountant]) -->|HTTPS Request| Middleware[CheckRole / BranchScope Middleware]
    Middleware -->|Authorized| Controller[Laravel Controllers / Livewire Components]
    
    subgraph Core Services Layer
        Controller -->|Journal Processing| JS[JournalService]
        Controller -->|Payroll Engine| PS[PayrollService]
        Controller -->|Report Aggregation| FRS[FinancialReportService]
        JS -->|Validate Balance| AS[AccountingService]
    end
    
    subgraph Persistence Layer
        AS -->|Write Journal Header| JE[(journal_entries)]
        AS -->|Write Journal Items| JI[(journal_items)]
        AS -->|Update Balances| COA[(chart_of_accounts)]
    end
🛠️ System Requirements
PHP: ^8.3

Composer: ^2.7

Node.js: ^20.x & NPM: ^10.x

Database: MySQL ^8.0 / MariaDB ^10.6

PHP Extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo_mysql, tokenizer, xml

⚙️ Getting Started
1. Clone & Dependencies
Bash
# Clone repository
git clone [https://github.com/billyanz/Sistem-Keuangan-General-Ledger-.git](https://github.com/billyanz/Sistem-Keuangan-General-Ledger-.git)
cd Sistem-Keuangan-General-Ledger-

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Frontend dependencies
npm install
2. Environment Configuration
Bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
Ubah baris konfigurasi database pada file .env:

Code snippet
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=keuangan_db
DB_USERNAME=root
DB_PASSWORD=
3. Database Migration & Seeding
Bash
# Run database migration & seed initial COA data
php artisan migrate:fresh --seed
4. Build Assets & Launch Server
Bash
# Build frontend assets (Development Mode)
npm run dev

# In a separate terminal, serve the application
php artisan serve
Aplikasi dapat diakses melalui browser pada http://127.0.0.1:8000.

📂 Key Directory Structure
Plaintext
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controller pelaporan & transaksi
│   │   └── Middleware/        # Multi-branch & role guards
│   ├── Livewire/              # Component dinamis (JournalForm, dll)
│   ├── Models/                # Eloquent Models (Journal, COA, Branch, dll)
│   ├── Services/              # Core business logic (Accounting, Payroll, Reports)
│   └── Traits/                # BelongsToBranch trait (Tenant isolation)
├── database/
│   ├── migrations/            # Skema tabel database
│   └── seeders/               # Master data COA & Branch seeder
├── resources/
│   └── views/                 # Blade templates & Tailwind UI layouts
└── routes/
    └── web.php                # Web routes & authentication guards
🧪 Code Quality & Testing
Projek ini dilengkapi dengan pengujian otomatis (Feature & Unit Tests) untuk memastikan akurasi kalkulasi keuangan dan keamanan alokasi data per cabang:

Bash
# Run all tests via Pest / PHPUnit
php artisan test

# Run specific feature tests
php artisan test --filter=CoreUiTest