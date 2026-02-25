<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diagnostics', function (Blueprint $table) {
            $table->id(); // int(11) NOT NULL AUTO_INCREMENT
            
            // Lié à la réception
            $table->integer('reception_id');
            $table->integer('utilisateur_id'); // Le mécanicien qui fait le diag
            
            // Détails techniques
            $table->text('description')->nullable();
            
            // Stockage JSON pour les codes erreurs (ex: ["P0300", "P0123"])
            $table->json('codes_pannes_json')->nullable();
            
            $table->dateTime('date_diagnostic')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostics');
    }
};
