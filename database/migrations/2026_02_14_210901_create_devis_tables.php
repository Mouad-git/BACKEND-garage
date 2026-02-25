<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table Entête du Devis
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->integer('diagnostic_id'); // Lien vers le diag du mécano
            $table->decimal('montant_ht', 10, 2)->default(0);
            $table->decimal('montant_ttc', 10, 2)->default(0);
            $table->enum('statut', ['brouillon', 'envoye', 'valide', 'refuse'])->default('brouillon');
            $table->dateTime('date_creation')->useCurrent();
        });

        // 2. Table de Détails (C'est ici que l'on sépare Pièces et Main d'oeuvre)
        Schema::create('lignes_devis', function (Blueprint $table) {
            $table->id();
            $table->integer('devis_id');
            
            // Si c'est une pièce, on stocke l'ID pour le stock
            $table->integer('piece_id')->nullable(); 
            
            // Permet de savoir si c'est du travail ou un objet
            $table->enum('type', ['main_doeuvre', 'piece']); 
            
            $table->string('description'); // ex: "Plaquettes de frein" ou "Main d'œuvre"
            $table->integer('quantite')->default(1); // Unités ou Heures
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('total_ligne', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_devis');
        Schema::dropIfExists('devis');
    }
};