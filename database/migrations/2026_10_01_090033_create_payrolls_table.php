<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('journal_id')->nullable()->constrained('journals')->nullOnDelete();
            $table->string('period', 7); // Format: YYYY-MM (misal: 2026-10)
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0); // Tunjangan
            $table->decimal('deductions', 15, 2)->default(0); // Potongan keterlambatan / absensi
            $table->decimal('pph21_amount', 15, 2)->default(0); // Potongan PPh 21
            $table->decimal('net_salary', 15, 2); // Gaji Bersih (THP)
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
