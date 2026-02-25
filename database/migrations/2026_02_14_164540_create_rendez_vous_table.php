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
        Schema::create('rendez_vous', function (Blueprint $table) {
        $table->id();
        $table->integer('client_id');
        $table->integer('vehicule_id');
        $table->integer('utilisateur_id')->nullable();
        $table->dateTime('date_heure');
        $table->string('type_intervention', 100)->nullable();
        $table->enum('statut', ['en_attente', 'confirme', 'honore', 'annule'])->default('en_attente');
        $table->text('notes')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
