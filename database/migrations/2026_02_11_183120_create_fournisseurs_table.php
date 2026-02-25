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
        Schema::create('fournisseurs', function (Blueprint $table) {
        $table->id();
        $table->string('nom_societe', 150)->nullable();
        $table->string('nom_contact', 100)->nullable();
        $table->string('telephone', 20)->nullable();
        $table->string('email', 100)->nullable();
        $table->text('adresse')->nullable();
        $table->enum('specialite', ['pieces_neuves', 'pieces_occasion', 'pneus'])->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
