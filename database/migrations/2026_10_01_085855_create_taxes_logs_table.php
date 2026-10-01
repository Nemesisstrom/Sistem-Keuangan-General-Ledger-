<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('journal_id')->constrained('journals')->cascadeOnDelete();
            $table->foreignId('tax_id')->constrained('taxes')->restrictOnDelete();
            $table->enum('type', ['input', 'output']); // Input = PPN Masukan / Pemotongan, Output = PPN Keluaran
            $table->decimal('taxable_amount', 15, 2); // DPP (Dasar Pengenaan Pajak)
            $table->decimal('tax_amount', 15, 2); // Nilai Pajak
            $table->string('tax_invoice_number')->nullable(); // Nomor Faktur Pajak / Bukti Potong
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_logs');
    }
};
