<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->cascadeOnDelete(); // Akun Kas/Bank terkait
            $table->date('statement_date'); // Tanggal Rekening Koran
            $table->decimal('book_balance', 15, 2); // Saldo di Buku Besar
            $table->decimal('bank_statement_balance', 15, 2); // Saldo di Rekening Koran
            $table->decimal('difference', 15, 2)->default(0); // Selisih (Harus 0 saat rekonsiliasi valid)
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliations');
    }
};
