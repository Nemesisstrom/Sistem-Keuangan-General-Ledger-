<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('entry_number', 50)->unique(); // e.g., JRNL-SR1-202610-0001
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable(); // Polymorphic source (e.g., Invoice, Payroll)
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('status', ['draft', 'posted'])->default('posted');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
