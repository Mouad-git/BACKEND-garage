<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {

            // 1️⃣ Rendre nom_societe obligatoire
            $table->string('nom_societe', 150)->nullable(false)->change();

            // 2️⃣ Ajouter nouvelle valeur ENUM
        });

        // ⚠️ Laravel ne gère pas bien la modification ENUM
        DB::statement("
            ALTER TABLE fournisseurs 
            MODIFY specialite 
            ENUM('pieces_neuves','pieces_occasion','pneus','consommables') 
            NOT NULL DEFAULT 'pieces_neuves'
        ");

        Schema::table('fournisseurs', function (Blueprint $table) {

            // 3️⃣ Ajouter nouvelles colonnes
            $table->string('conditions_paiement', 100)
                  ->default('Comptant')
                  ->after('specialite');

            $table->timestamp('date_creation')
                  ->useCurrent()
                  ->after('conditions_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {

            $table->string('nom_societe', 150)->nullable()->change();

            $table->dropColumn(['conditions_paiement', 'date_creation']);
        });

        DB::statement("
            ALTER TABLE fournisseurs 
            MODIFY specialite 
            ENUM('pieces_neuves','pieces_occasion','pneus') 
            NULL
        ");
    }
};
