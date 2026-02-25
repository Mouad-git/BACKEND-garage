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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
        $table->integer('ordre_id'); // Lien vers ordres_reparations
        $table->integer('technicien_id')->nullable();
        $table->string('libelle', 150);
        $table->decimal('temps_estime', 5, 2)->nullable();
        $table->decimal('temps_reel', 5, 2)->nullable();
        // Statut de la tâche : 'en_attente' ou 'termine'
        $table->string('statut', 50)->default('en_attente'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
