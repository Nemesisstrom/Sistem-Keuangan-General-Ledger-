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
            $table->string('code', 10)->unique(); // Misal: PPN11, PPH21, PPH23
            $table->string('name'); // PPN Keluaran, PPN Masukan, PPh Pasal 21, dll.
            $table->enum('category', ['PPN', 'PPh']);
            $table->decimal('rate', 5, 2); // Persentase pajak, misal: 11.00, 5.00, 2.00
            $table->foreignId('account_id')->constrained('chart_of_accounts')->restrictOnDelete(); // Akun COA penampung pajak
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
