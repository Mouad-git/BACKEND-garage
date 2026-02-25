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
        Schema::create('pieces', function (Blueprint $table) {
        $table->id();
        $table->integer('fournisseur_id')->nullable();
        $table->string('reference', 100)->nullable();
        $table->string('designation', 150)->nullable();
        $table->enum('type', ['neuf', 'occasion'])->default('neuf');
        $table->decimal('prix_achat_defaut', 10, 2)->nullable();
        $table->decimal('prix_vente_public', 10, 2)->nullable();
        $table->integer('stock_actuel')->default(0);
        $table->integer('seuil_alerte')->default(5);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces');
    }
};
