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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->enum('type', ['email', 'sms'])->default('sms');
            $table->text('contenu')->nullable();
            $table->string('statut')->default('distribue'); // Ajout pour le suivi
            $table->dateTime('date_envoi')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
