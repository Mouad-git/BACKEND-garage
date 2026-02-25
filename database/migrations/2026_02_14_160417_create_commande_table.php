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
        // Table Entête
    Schema::create('commandes_fournisseurs', function (Blueprint $table) {
        $table->id();
        $table->integer('fournisseur_id');
        $table->integer('utilisateur_id')->nullable();
        $table->string('numero_commande', 50);
        $table->enum('statut', ['brouillon', 'envoye', 'recu', 'annule'])->default('envoye');
        $table->decimal('total_ht', 10, 2);
        $table->dateTime('date_commande')->useCurrent();
        $table->dateTime('date_reception_reelle')->nullable();
    });

    // Table Détails
    Schema::create('lignes_commandes_fournisseurs', function (Blueprint $table) {
        $table->id();
        $table->integer('commande_id');
        $table->integer('piece_id');
        $table->integer('quantite_commandee');
        $table->decimal('prix_achat_unitaire_reel', 10, 2);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande');
    }
};
