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
        Schema::create('ordres_reparations', function (Blueprint $table) {
        $table->id();
        $table->integer('devis_id'); 
        $table->integer('vehicule_id'); // <-- AJOUTE CETTE LIGNE ICI
        $table->enum('statut', ['en_attente','en_cours','controle_qualite','termine'])->nullable();
        $table->date('date_debut')->nullable();
        $table->date('date_fin_prevue')->nullable();
        $table->date('date_fin_reelle')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordres_reparations');
    }
};
