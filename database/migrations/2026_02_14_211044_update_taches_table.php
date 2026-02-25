<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('taches', function (Blueprint $table) {

            // Modifier libelle (supprimer limite 150)
            $table->string('libelle')->change();

            // Ajouter date_debut_tache
            $table->timestamp('date_debut_tache')
                  ->nullable()
                  ->after('statut');
        });

        // Modifier statut en ENUM proprement
        DB::statement("
            ALTER TABLE taches 
            MODIFY statut 
            ENUM('en_attente','termine') 
            NOT NULL DEFAULT 'en_attente'
        ");
    }

    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {

            $table->string('libelle', 150)->change();

            $table->dropColumn('date_debut_tache');
        });

        DB::statement("
            ALTER TABLE taches 
            MODIFY statut 
            VARCHAR(50) 
            NOT NULL DEFAULT 'en_attente'
        ");
    }
};
