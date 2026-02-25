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
        Schema::create('vehicules', function (Blueprint $table) {
             $table->id(); // int(11) NOT NULL AUTO_INCREMENT
            // Clé étrangère vers la table clients
            $table->unsignedBigInteger('client_id'); 
            $table->string('immatriculation', 20)->nullable();
            $table->string('vin', 50)->nullable();
            $table->string('marque', 100)->nullable();
            $table->string('modele', 100)->nullable();
            $table->integer('annee')->nullable();
            $table->integer('kilometrage_actuel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
