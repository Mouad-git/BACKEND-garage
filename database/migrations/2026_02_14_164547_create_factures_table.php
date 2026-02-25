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
        $table->id();
        $table->integer('ordre_id')->nullable();
        $table->integer('client_id')->nullable();
        $table->string('numero_facture', 50)->nullable();
        $table->decimal('montant_total_ttc', 10, 2)->nullable();
        $table->string('mode_paiement', 50)->nullable();
        $table->string('statut_paiement', 50)->nullable();
        $table->dateTime('date_facture')->useCurrent(); // correspond à current_timestamp()
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
