<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // e.g., PPN-OUT, PPH21, PPH23
            $table->string('name');
            $table->decimal('rate', 5, 2); // e.g., 11.00 for PPN 11%
            $table->enum('type', ['vat_input', 'vat_output', 'pph21', 'pph23', 'pph4_2', 'other']);
            $table->foreignId('account_id')->constrained('chart_of_accounts')->restrictOnDelete(); // COA Utang/Piutang Pajak
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
