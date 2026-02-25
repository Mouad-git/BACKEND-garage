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
        Schema::create('receptions', function (Blueprint $table) {
             $table->id(); // int(11) NOT NULL AUTO_INCREMENT
            
            // Clés étrangères / IDs
            $table->integer('vehicule_id');
            $table->integer('utilisateur_id');
            $table->integer('rendez_vous_id')->nullable();
            
            // Infos réception
            $table->dateTime('date_reception')->useCurrent();
            $table->text('motif_visite')->nullable();
            
            // Stockage JSON pour les photos
            $table->json('photos_json')->nullable();
            
            // État de la réception
            $table->enum('statut', ['en_attente', 'diagnostique', 'termine'])->default('en_attente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptions');
    }
};
