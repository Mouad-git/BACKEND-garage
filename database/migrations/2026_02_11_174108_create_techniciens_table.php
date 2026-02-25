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
        Schema::create('techniciens', function (Blueprint $table) {
            $table->id(); 

            // `utilisateur_id` int(11) DEFAULT NULL (Lien vers la table users)
            $table->integer('utilisateur_id')->nullable();

            // `specialite` enum('mecanique','carrosserie','electricite')
            $table->enum('specialite', ['mecanique', 'carrosserie', 'electricite'])->nullable();

            // `niveau_expertise` enum('Junior','Confirme','Expert')
            $table->enum('niveau_expertise', ['Junior', 'Confirme', 'Expert'])->nullable();

            // `tarif_horaire_interne` decimal(10,2)
            $table->decimal('tarif_horaire_interne', 10, 2)->nullable();

            // `disponibilite` tinyint(1) DEFAULT 1 (1 = disponible, 0 = occupé)
            $table->boolean('disponibilite')->default(true);

            // `statut_actif` tinyint(1) DEFAULT 1 (1 = actif, 0 = supprimé/archivé)
            $table->boolean('statut_actif')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('techniciens');
    }
};
