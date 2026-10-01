<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->date('transaction_date');
            $table->string('reference_number')->unique(); // Misal: INV/SR1/202610/001, PAY/SR2/202610/001
            $table->string('description');
            $table->enum('status', ['draft', 'posted', 'void'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['branch_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
