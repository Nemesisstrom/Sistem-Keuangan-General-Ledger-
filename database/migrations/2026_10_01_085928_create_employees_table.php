<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('nik', 20)->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('position');
            $table->decimal('basic_salary', 15, 2);
            $table->string('npwp', 25)->nullable();
            $table->enum('ptkp_status', ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'])->default('TK/0');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
