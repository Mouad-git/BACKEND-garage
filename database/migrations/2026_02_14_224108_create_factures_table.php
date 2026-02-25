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
        Schema::create('factures', function (Blueprint $table) {
            // `id` int(11) NOT NULL AUTO_INCREMENT
            $table->id(); 

            // `ordre_id` int(11) DEFAULT NULL (Lien vers l'ordre de réparation)
            $table->integer('ordre_id')->nullable();

            // `client_id` int(11) DEFAULT NULL (Lien vers le client)
            $table->integer('client_id')->nullable();

            // `numero_facture` varchar(50) DEFAULT NULL (Ex: FA-2026-0001)
            $table->string('numero_facture', 50)->nullable();

            // `montant_total_ttc` decimal(10,2) DEFAULT NULL
            $table->decimal('montant_total_ttc', 10, 2)->nullable();

            // `mode_paiement` varchar(50) DEFAULT NULL (CB, Espèces, Chèque...)
            $table->string('mode_paiement', 50)->nullable();

            // `statut_paiement` varchar(50) DEFAULT NULL (Payé, En attente...)
            $table->string('statut_paiement', 50)->nullable();

            // `date_facture` datetime DEFAULT current_timestamp()
            $table->dateTime('date_facture')->useCurrent();

            // Optionnel : Si tu veux utiliser les relations Laravel classiques, 
            // il vaut mieux utiliser foreignId pour ordre_id et client_id.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};